/*
 * Copyright (c) 2007, Nico Leidecker
 * All rights reserved.
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the organization nor the names of its contributors 
 *       may be used to endorse or promote products derived from this software 
 *       without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE REGENTS AND CONTRIBUTORS ``AS IS'' AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

#include <errno.h>
#include <dirent.h>
#include <openssl/ssl.h>
#include <signal.h>
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <sys/stat.h>
#include <sys/types.h>
#include <sys/wait.h>
#include <unistd.h>

#include "phrasendrescher.h"
#include "utils.h"
#include "worker.h"
#include "source.h"
#include "rules.h"

struct keys_t **
get_key_files(char *keys, int *filenum)
{
	DIR *dir;
	FILE *file;
	char path[MAX_PATH_LENGTH];
	struct stat key_stat;
	struct dirent *entry;
	struct keys_t **k;
	
	if (stat(keys, &key_stat) == -1) {
		error_printf("stat failed for %s: %s\n", keys, strerror(errno));
		return 0;
	}

	*filenum = 0;
	k = 0;

	if (S_ISDIR(key_stat.st_mode)) {
		
		// read in every file in directory
		dir = opendir(keys);
		if (!dir) {
			error_printf("could not open directory %s: %s\n",
                         								keys, strerror(errno));
			return 0;
		}
		
		while((entry = readdir(dir))) {
			// skip everything beginning with a dot
			if (*(entry->d_name) != '.') {
				
				if (keys[strlen(keys) - 1] == '/') {
                	snprintf(path,MAX_PATH_LENGTH,"%s%s", keys, entry->d_name);
				} else {
					snprintf(path,MAX_PATH_LENGTH,"%s/%s", keys, entry->d_name);
				}
				file = fopen(path, "r"); 
				if (!file) {
					error_printf("could not open file %s: %s\n",
                                 						path, strerror(errno));
				} else {
					k = (struct keys_t **) realloc(k,
                    				sizeof(struct keys_t *) * (*filenum + 1));
					
					k[*filenum] = (struct keys_t *)malloc(sizeof(struct keys_t));
					k[*filenum]->fn = strdup(path);
					k[*filenum]->fp = file;
					k[*filenum]->id = *filenum;
					
					(*filenum)++;
					
				}
			}
		}
	} else {		
		// read in a single key file
		file = fopen(keys, "r");
		if (!file) {
			error_printf("could not open file %s: %s\n", path, strerror(errno));
		} else {
			k = (struct keys_t **) malloc(sizeof(struct keys_t *));
			*k = (struct keys_t *) malloc(sizeof(struct keys_t));
			(*k)->fn = strdup(keys);
			(*k)->fp = file;
			(*k)->id = 0;
			
			(*filenum)++;
		}
	}
		
	return k;
}

void banner()
{
	printf("phrasen|drescher 1.0 - the passphrase cracker\n");
	printf("Copyright (C) 2007 Nico Leidecker; nfl@portcullis-security.com\n\n");
}

void usage(char *path)
{
	printf("Usage: %s [options] (file|directory) \n", path);
	printf(" Options:\n");
	printf("   h           : print this message\n");
	printf("   v           : verbose mode\n");
    printf("   i from[:to] : incremental mode beginning with word length `from'\n");
    printf("                 and going to `to'\n");
	printf("   d file      : run dictionary based with words from `file'\n");
	printf("   r rules 	   : specify rewriting rules for the dictionary mode:\n");
	printf("                   A = all characters upper case\n");
	printf("                   F = first character upper case\n");
	printf("                   L = last character upper case\n");
	printf("                   W = first letter of each word to upper case\n");
	printf("                   a = all characters lower case\n");
	printf("                   f = first character lower case\n");
	printf("                   l = last character lower case\n");
	printf("                   w = first letter of each word to lower case\n");
	printf("                   D = prepend digit\n");
	printf("                   d = append digit\n");
	printf("                   e = 1337 characters\n");
	printf("                   x = all rules\n\n");
	
	printf(" Environment Variables::\n");
	printf("   PHRASENDRESCHER_MAP	: the characters for the incremental mode are\n");
	printf("					  	  taken from a character list. A customized list\n");
	printf("                      	  can be specified in the environment variable\n\n");
	
	printf(" Example:\n");
	printf("   export PHRASENDRESCHER_MAP=\"abcdefghijklmnopqrstuvwxyz\"\n");
	printf("   %s -i 6:8 key-file\n", path);
}

void teardown()
{
	reset_tty();
	printf("bye, bye...\n");
}

struct keys_t **
parse_opts(int argc, char **argv, int *filenum)
{
	struct source_t source = { { { 0  }  } };
	int o, mode = -1;

	verbose = 0;
	// parse options
	while((o = getopt(argc, argv, "hvi:d:r:")) != -1) {
		switch(o) {
			case 'h':
				usage(*argv);
				return 0;
			case 'v':
				verbose = 1;
				break;
			case 'i':
				if (sscanf(optarg, "%i:%i",
					&source.un.incremental.from,
					&source.un.incremental.to) < 2) {
						source.un.incremental.from = atoi(optarg);
						source.un.incremental.to = atoi(optarg);
					}
                // get the char map, if there is one
					source.un.incremental.map = getenv("PHRASENDRESCHER_MAP");
					mode = SOURCE_MODE_INCREMENTAL;
					break;
			case 'd':
				source.un.dictionary.path = strdup(optarg);
				mode = SOURCE_MODE_DICTIONARY;
				break;
			case 'r':
				while(*optarg) {
					switch(*optarg) {
						case 'A':
							source.un.dictionary.rules |= RULES_ALL_UPPER;
							break;
						case 'F':
							source.un.dictionary.rules |= RULES_FIRST_UPPER;
							break;
						case 'L':
							source.un.dictionary.rules |= RULES_LAST_UPPER;
							break;
						case 'W':
							source.un.dictionary.rules |= RULES_UPPER_WORD_BEGINNING;
							break;
						case 'a':
							source.un.dictionary.rules |= RULES_ALL_LOWER;
							break;
						case 'f':
							source.un.dictionary.rules |= RULES_FIRST_LOWER;
							break;
						case 'l':
							source.un.dictionary.rules |= RULES_LAST_LOWER;
							break;
						case 'w':
							source.un.dictionary.rules |= RULES_LOWER_WORD_BEGINNING;
							break;
						case 'D':
							source.un.dictionary.rules |= RULES_PREPEND_DIGIT;
							break;
						case 'd':
							source.un.dictionary.rules |= RULES_APPEND_DIGIT;
							break;
						case 'e':
							source.un.dictionary.rules |= RULES_1337;
							break;
						case 'x':
							source.un.dictionary.rules = RULES_ALL_UPPER
									|	RULES_FIRST_UPPER
									|	RULES_LAST_UPPER
									|	RULES_ALL_LOWER
									|	RULES_FIRST_LOWER
									|	RULES_LAST_LOWER
									|	RULES_PREPEND_DIGIT
									|	RULES_APPEND_DIGIT
									|	RULES_1337
									|	RULES_UPPER_WORD_BEGINNING
									|	RULES_LOWER_WORD_BEGINNING;
							break;
					}
					optarg++;
				}
				break;
			default:
				error_printf("unrecognized option -%c\n", o);
				usage(*argv);
				return 0;
		}
	}

	// set mode
	if (mode != -1) {
		if (!source_init(mode, &source)) {
			error_printf("initializing dictionary %s: %s\n",
						 source.un.dictionary.path, strerror(errno));
			return 0;
		}
	} else {
		error_printf("you have to specify a mode to run in\n");
		usage(*argv);
		return 0;
	}
	
	// get key files
	if (optind == argc - 1) {
		return get_key_files(argv[optind], filenum);
	}

	usage(*argv);
	return 0;
}

int main(int argc, char **argv)
{
	struct keys_t **kf;
	int st, i, keynum;
	int pid;
	
	// print banner
	banner();

	kf = parse_opts(argc, argv, &keynum);
	if (!kf) {
		return -1;
	}
	
	// print key files
	verbose_printf("%i key files read:\n", keynum);
	for (i = 0; i < keynum; i++) {
		verbose_printf("%i): %s\n", i, kf[i]->fn);
	}
	
	// prepare openssl
	OpenSSL_add_all_algorithms();
	
	// run
	terminate = 0;
	stats = 0;
	signal(SIG_TERMINATE, signal_terminate);
		
	pid = fork();
	if (pid == 0) {
		verbose_printf("\nrunning ...\n");
		signal(SIG_STATS, signal_stats);
		worker_run(kf, keynum);
		kill(0, SIG_TERMINATE);
		printf("finished!\n");
	} else {
		atexit(teardown);
		prepare_tty();
		handle_user_input(pid);
		wait(&st);
	} 

	return 0;
}

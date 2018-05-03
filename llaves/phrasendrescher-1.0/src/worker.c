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

#include <stdio.h>
#include <string.h>
#include <openssl/pem.h>

#include "phrasendrescher.h"
#include "utils.h"
#include "source.h"


static void
unset_key(struct keys_t **k) 
{
	free((*k)->fn);
	fclose((*k)->fp);
	free((*k));
	(*k) = 0;
}

static int 
try_phrase(struct keys_t **kf, char* phrase, int keynum)
{
	int matches;
	int k;
	
	matches = 0;
	for (k = 0; k < keynum; k++) {
		if (kf[k]) {
			rewind(kf[k]->fp);
			if (PEM_read_PrivateKey(kf[k]->fp, 0, 0, phrase)) {
			    if (strlen(phrase) == 0) {
				printf("match: (%i) %s {empty passphrase}\n", kf[k]->id, kf[k]->fn, phrase);
			    } else {
			        printf("match: (%i) %s [%s]\n", kf[k]->id, kf[k]->fn, phrase);
			    }
			    matches++;
			    unset_key(&(kf[k]));
			}		
		}
	}
	
	return matches;
}

static void
print_stats(int nphrases, char *phrase, int keynum, int matches)
{
	printf("phrases tried: %i (latest: %s)  keys: %i  matches: %i\n",
           									nphrases, phrase, keynum, matches);
}

int 
worker_run(struct keys_t **kf, int keynum)
{
	char *phrase = "";
	int nphrases, matches;

	nphrases = 0;
	matches = 0;
	
	do {
		if ((matches += try_phrase(kf, phrase, keynum)) > 0) {
		    if (matches == keynum) {
		    	terminate = 1;
		    }
		}
		
		nphrases++;
		
		if (stats) {
			print_stats(nphrases, phrase, keynum, matches);
			stats = 0;
		}
		
	} while((phrase = source_get_word()) && !terminate);
	
	return terminate;
}


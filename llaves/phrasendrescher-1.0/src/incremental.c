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
#include <stdlib.h>
#include <string.h>

#include "incremental.h"
#include "utils.h"

// incremental mode
static int from, to;
static int *mi;
static char *map = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ 0123456789-_.,+:;!\"$%^&*()[]{}@#~'?/\\<>|";


static void
incremental_reset(char *word, int length)
{
	memset(word, map[0], length);
	word[length] = '\0';
	
	mi = (int *) malloc(length * sizeof(int));
	memset(mi, 0x00, length * sizeof(int));
		
	mi[length - 1] = -1;
}

static int
incremental_do(char *word, int length)
{
	while(length--) {
		word[length] = map[mi[length] + 1];
		if (mi[length] != strlen(map) - 1) {
			mi[length]++;
			break;
		} else {
			word[length] = map[0];
			mi[length] = 0;
			if (length == 0) {
				return 0;
			}
		}
	}
	
	return 1;
}

int
incremental_get_word(char *word)
{
	static int length = 0;
	
	if (length == 0) {
		length = from;
		incremental_reset(word, length);
	}
	
	if (!incremental_do(word, length)) {
		length++;
		if (length > to) {
			return 0;
		} else {
			incremental_reset(word, length);
		}
	}	
	
	return 1;
}

int
incremental_init(int inc_from, int inc_to, char *custom_map)
{
	from = inc_from;
	to = inc_to;

	if (custom_map) {
		map = custom_map;
		verbose_printf("using customized maps: %s\n", map);
	}
	
	return 1;
}

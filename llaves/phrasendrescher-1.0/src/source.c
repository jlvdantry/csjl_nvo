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

#include "source.h"
#include "phrasendrescher.h"
#include "utils.h"
#include "dictionary.h"
#include "incremental.h"

static int (*source_callback)(char *) = 0;

int
source_init(int mode, struct source_t *source)
{
	switch(mode) {
		case SOURCE_MODE_DICTIONARY:
			if (!dictionary_init(source->un.dictionary.path, source->un.dictionary.rules)) {
				return 0;
			}
			
			source_callback = dictionary_get_word;
			
			verbose_printf("mode: dictionary (%s)\n",
						   						source->un.dictionary.path);
			break;
		case SOURCE_MODE_INCREMENTAL:
			if (!incremental_init(source->un.incremental.from,
				 						source->un.incremental.to,
				 							source->un.incremental.map)) {
					 return 0;
			}

			source_callback = incremental_get_word;
			
			verbose_printf("mode: incremental from %i to %i\n",
											source->un.incremental.from,
												source->un.incremental.to);
			break;
	}
	
	return 1;
}

char *
source_get_word() 
{
	static char *word = 0;
	if (!word) {	
		word = (char *) malloc(MAX_LINE_LENGTH);
	}
	
	if (source_callback(word)) {
		return word;
	}
	
	return 0;
}

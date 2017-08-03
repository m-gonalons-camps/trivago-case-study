<?php

namespace AppBundle\Service;

class DefaultAnalyzerConstants {

    const NEGATORS = [
        "not",
        "isn't",
        "aren't",
        "wasn't",
        "weren't",
        "doesn't",
        "didn't",
        "won't",
        "wouldn't",
        "shouldn't",
        "don't",
        "isnt",
        "arent",
        "wasnt",
        "werent",
        "doesnt",
        "didnt",
        "wont",
        "wouldnt",
        "shouldnt",
        "dont",
        "no"
    ];

    const EMPHASIZERS = [
        'extremely',
        'exceedingly',
        'exceptionally',
        'especially',
        'tremendously',
        'immensely',
        'vastly',
        'hugely',
        'extraordinarily',
        'extra',
        'excessively',
        'overly',
        'very',
        'absolutely',
        'abundantly',
        'inordinately',
        'singularly',
        'significantly',
        'distinctly',
        'outstandingly',
        'uncommonly',
        'unusually',
        'decidedly',
        'particularly',
        'eminently',
        'supremely',
        'highly',
        'remarkably',
        'really',
        'truly',
        'mightily',
        'thoroughly',
        'most',
        'so',
        'too',
        'terrifically',
        'awfully',
        'terribly',
        'devilishly',
        'madly',
        'majorly',
        'seriously',
        'desperately',
        'mega',
        'ultra',
        'stinking',
        'damn',
        'frightfully'
    ];

    const EMPHASIZERS_SCORE_MODIFIER = .5;

    const NEGATED_NEGATIVE_CRITERIA_SCORE_MODIFIER = -0.1;

    const NEGATED_POSITIVE_CRITERIA_SCORE_MODIFIER = -1;

}
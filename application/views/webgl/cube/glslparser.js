/**
 * WebGL Shader Language Parser
 * an additional Parser for syntax.js
 *
 * The one which makes me figure out to use
 * parser factories, strategies and template methods
 * in the parsing system to make it variable.
 */

/**
 * The tokens of WebGL in tables for the lexer and parser
 */
var vshader_builtins = {
    "gl_Position" : true
};
var fshader_builtins = {
    "gl_FragColor" : true
};

var storage_qualifiers = {
    "attribute": true,
    "varying": true,
    "uniform": true,
    "struct": true,
    "void": true
};

var precision_qualifiers = {
    "highp": true,
    "mediump": true,
    "lowp": true
};
var keywords = {
    "precision": true
};
var builtin_types = {
    "vec2": true,
    "vec3": true,
    "vec4": true,
    "mat2": true,
    "mat3": true,
    "mat4": true,
    "bvec2": true,
    "bvec3": true,
    "bvec4": true,
    "ivec2": true,
    "ivec3": true,
    "ivec4": true,
    "sampler2D": true,
    "samplerCube": true,
    "float": true,
    "bool":true,
    "int": true,
    "void": true
};

var operatorChar = {
    "(":true,
    ")":true,
    "[":true,
    "]":true,
    "{":true,
    "}":true,
    "!":true,
    "*":true,
    "-":true,
    "/":true,
    "%":true,
    "+":true,
    ".":true
};

var token;
var token2;
var q = 0;

function nextToken() {
    "use strict";
}

var ch;
var ch2;
var p = 0;
var tok;
var tokens = [];
function nextChar() {
    "use strict";
    if (p < source.length) {
        p+=1;
        ch = ch2;
        ch2 = source[p+1];
        return ch;
    } else {
        return null;
    }
}
function initTokenize(source) {
    "use strict";
    p = -1;
    ch2 = source[0];
    tokens = [];
}

/**
 * These function test the actual char and lookahead
 */
function isIdentifierStart() {
    "use strict";
    return (/[A-Za-z]/).test(ch);
}
function isIdentifierPart() {
    "use strict";
    return (/[A-Za-z0-9$_]/.test(ch));
}
function isNumber() {
    "use strict";
    return (/[\d]/.test(ch));
}
function isOperator() {
    "use strict";
    return !!operatorSymbol[ch];
}
/**
 * These function make a token out of the expected string or fail
 */
function lexIdentifier() {
    "use strict";
    var id = ch;
    do {
        if (isIdentifier() || isNumber()) id += ch;
        else {
            // if isKeyword[id] type = "Keyword"
            tok = {
                type: "Identifier",
                value: id
            };
            tokens.push(tok);
            return tok;
        }
    } while (nextChar() != null);
    return false;
}
function lexNumber() {
    "use strict";
    do {
        if (isNumber()) num += ch;
        else {

            tok = {
                type: "NumericLiteral",
                value: num
            };
            tokens.push(tok);
            return tok;
        }
    } while (nextChar() != null);
    return false;
}
function lexOperator() {
    "use strict";
    if (braces[ch]) {
        return makeToken("Punctuator", ch);
    }
}

function makeToken(type, value) {
    "use strict";
    tok = {
        type: type,
        value: value
    };
    tokens.push(tok);
}
/**
 * tokenize returns an array of tokens.
 */
function tokenize(source) {
    "use strict";
    initTokenize(source);
    nextChar(); // first character
    while (ch !== null) {
        if (isIdentifier()) {
            lexIdentifier();
        } else if (isNumber()) {
            lexNumber();
        } else if (isOperator()) {
            lexOperator();
        }
    }
}
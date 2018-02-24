---
currentMenu: index
---
# Embed scripts in your PHP Applications

Phlip (pronounced \\ˈflip\\) is a scripting language based on
[s-expressions](https://en.wikipedia.org/wiki/S-expression) with a twist: it considers `,` and `:` as
[whitespaces](https://en.wikipedia.org/wiki/Whitespace_character) and supports
[JSON](https://www.json.org)-alike declarations for [vectors](https://en.wikipedia.org/wiki/Array_data_structure)
(arrays) and [maps](https://en.wikipedia.org/wiki/Associative_array) (objects or associative arrays).

> &laquo; _It's literally JSON with closures._ &raquo;

[Webgraphe Phlip](https://github.com/webgraphe/phlip) is a [PHP](https://www.php.net) project consisting of a
[lexer](https://en.wikipedia.org/wiki/Lexical_analysis) and a
[parser](https://en.wikipedia.org/wiki/Parsing#Computer_languages) with a bunch of
[contracts](https://en.wikipedia.org/wiki/Protocol_(object-oriented_programming)) allowing developers to declare a
[dialect](https://en.wikipedia.org/wiki/Programming_language#Dialects,_flavors_and_implementations) of their own and
script with it in their applications.

At its root, Phlip can be used to tokenize and parse any script observing its syntax rules. The behavior of a script
depends strictly on the context it's executed in, _i.e._ the named container for symbols. To this end, one can bootstrap
a context with the Phlipy dialect loaded in and start scripting with Phlip immediately.

For example, the following Phlipy script declares a map with a `factorial-of-20` property being assigned the result of
a [named-let](https://www.gnu.org/software/mit-scheme/documentation/mit-scheme-ref/Iteration.html)
(based off MIT-scheme) recursively calculating the [factorial](https://en.wikipedia.org/wiki/Factorial) of `20`:
```json
; Returns an object containing the factorial of 20
{
    "factorial-of-20": (let
        factorial
        ((x 20))
        (if
            (< x 2)
            1
            (* x (factorial (- x 1)))
        )
    )
}
```

The program loaded by the script would execute all statements and return the result from the last executed one.
In this case, it would yield:
```json
{
    "factorial-of-20": 2432902008176640000
}
```

Phlip ships with the [CLI](https://en.wikipedia.org/wiki/Command-line_interface) `phlip` script, a literal
[read-eval-print loop](https://en.wikipedia.org/wiki/Read%E2%80%93eval%E2%80%93print_loop) (REPL) to experiment with
the Phlipy dialect. It also simplifies [unit testing](https://en.wikipedia.org/wiki/Unit_testing) with the CLI
`phlipunit` script, built on top of [PHPUnit](https://phpunit.de/).

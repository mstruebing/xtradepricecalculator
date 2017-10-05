# XTradePriceCalculator

This is a small HTTP-service to calculate the prices of the goods for the XTrade game 
in dependence of the current available amounts of goods

## Requirements

[PHP 7.1 is required as type hints inclusive void is used.](https://wiki.php.net/rfc/void_return_type)

## Usage 

To use this service you have to make an HTTP-request with five different GET-parameters
where everyone has to be set.
The five parameters are: `iron`, `water`, `food`, `steel` and `electronics` and 
every single one have to be an integer value.

The correct request address and port is dependent on where this service is running.

In case it is running on localhost on port 8000 it could be called like this:

`curl '127.0.0.1:8000?iron=30&water=5&food=2&steel=50000&electronics=100'`

## Development

For Development please make sure that your editor reads the `.editorconfig`.  
Also `composer` should be used to comfortable install and use some code quality tools.  
Currently two different code quality tools are used `editorconfig-checker` to make sure   
all files are respecting the `.editorconfig` and `PHP_CodeSniffer` to make sure   
the code implements the PSR-2 coding standard.   
Because of the small size of this service we currently violate one rule   
but this should stay exactly at this number.  
To install these dependencies it is enough to do a simple `composer install`.  
To invoke the both tools you could just type `composer lint` int your terminal.  
To invoke just one you could either type `composer lint:editorconfig` or `composer lint:psr2`.  

The mentioned error which is expected is this:

```
A file should declare new symbols (classes, functions,
constants, etc.) and cause no other side effects, or
it should execute logic with side effects, but should
not do both. The first symbol is defined on line 19
and the first side effect is on line 106.
```


Further information to the mentioned tools:
[Editorconfig](http://editorconfig.org/)  
[editorconfig-checker](https://github.com/editorconfig-checker/editorconfig-checker.php)  
[PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)  
[PSR-2 Coding Style](http://www.php-fig.org/psr/psr-2/)

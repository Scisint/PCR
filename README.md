Scisint PCR
=======
ver 1.1.0

专门用来PCR检测仪结果判定


安装
------------

```bash
composer require scisint/pcr
```

使用
-----
直接调用静态方法。可以使用Pcr中的静态常量来作为值判断。

注意参数的顺序为: ORF1ab值、N值、IC值
```php
<?php
Pcr::check('ORF1ab','N','IC','对照');
//返回结果
/*
array(4) {
    ["result"]=>
    string(6) "阳性"
    ["is_positive"]=>
    int(1)
    ["state"]=>
    string(8) "positive"
    ["rule_code"]=>
    string(5) "1_1_1"
    ["type_warning"]=>
    int(0)
}
*/

```
返回值
-----
result：结果
is_positive: 是否阳性，可能值为 1(是)，0(否)，-1(未知)
state: 判断阴阳性英文描述，可能值为 positive,negative,unknown
rule_code: 规则编号，可用来检查结果
type_warning: 根据类型返回的警告级别

常量值
-----
```php
<?php
Pcr::RULE_NOT_FIND        = '未知结果';
Pcr::RULE_INVALID         = '无效结果';
Pcr::RULE_SINGLE_POSITIVE = '单阳';
Pcr::RULE_POSITIVE        = '阳性';
Pcr::RULE_NEGATIVE        = '阴性';
Pcr::RULE_WARNING         = '警告';
Pcr::RULE_NOCT            = 'NOCT警告';
Pcr::IS_POSITIVE          = 1;
Pcr::IS_NEGATIVE          = 0;
Pcr::IS_UNKNOWN           = -1;
Pcr::POSITIVE             = 'positive';
Pcr::NEGATIVE             = 'negative';
Pcr::UNKNOWN              = 'unknown';
Pcr::NOCT                 = 'NOCT';
Pcr::BLANK                = '';
Pcr::ORF1AB               = 'ORF1ab';
Pcr::N                    = 'N';
Pcr::IC                   = 'IC';
Pcr::TYPE_WARNING         = 1;
Pcr::TYPE_WARNING_DEFAULT = 0;
```

使用举例：
-----
```php
<?php

use Scisint\PCR\Pcr;

var_dump(Pcr::check('NoCt','NoCt',38.30));

```

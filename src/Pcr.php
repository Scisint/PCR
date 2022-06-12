<?php

namespace Scisint\PCR;

// 为SLAN PCR仪核酸检测结果制作
// version 1.0.2
// update 2022-06-12 17:30
class Pcr
{
    public const RULE_NOT_FIND        = '未知结果';
    public const RULE_INVALID         = '无效结果';
    public const RULE_SINGLE_POSITIVE = '单阳';
    public const RULE_POSITIVE        = '阳性';
    public const RULE_NEGATIVE        = '阴性';
    public const RULE_WARNING         = '警告';
    public const RULE_NOCT            = 'NOCT警告';
    public const IS_POSITIVE          = 1;
    public const IS_NEGATIVE          = 0;
    public const IS_UNKNOWN           = -1;
    public const POSITIVE             = 'positive';
    public const NEGATIVE             = 'negative';
    public const UNKNOWN              = 'unknown';
    public const NOCT                 = 'NOCT';
    public const BLANK                = '';
    public const ORF1AB               = 'ORF1ab';
    public const N                    = 'N';
    public const IC                   = 'IC';

    // 将规则表定义为类常量，可以在后期传入自己的规则集
    // 规则列表,格式是[ruleset_id=>['rule_code'=>['result_text'=>'阳性/阴性','is_postive'=>1/0,]
    public static $rule = [
        'BS_1'=>[
            'range'=>[
                'ORF1ab' => [39.5,10],
                'N'      => [39.5,10],
                'IC'     => [38],
            ],
            'data'=>[
                'NOCT_NOCT_NOCT' => ['result' => self::RULE_NOCT,'is_positive' => self::IS_UNKNOWN],
                '0_0_0'          => ['result' => self::RULE_INVALID,'is_positive' => self::IS_UNKNOWN],
                '0_1_0'          => ['result' => self::RULE_SINGLE_POSITIVE,'is_positive' => self::IS_POSITIVE],
                '0_2_0'          => ['result' => self::RULE_INVALID,'is_positive' => self::IS_UNKNOWN],
                '0_-1_0'         => ['result' => self::RULE_WARNING,'is_positive' => self::IS_UNKNOWN],
                '1_0_0'          => ['result' => self::RULE_SINGLE_POSITIVE,'is_positive' => self::IS_POSITIVE],
                '1_1_0'          => ['result' => self::RULE_POSITIVE,'is_positive' => self::IS_POSITIVE],
                '1_2_0'          => ['result' => self::RULE_POSITIVE,'is_positive' => self::IS_POSITIVE],
                '1_-1_0'         => ['result' => self::RULE_SINGLE_POSITIVE,'is_positive' => self::IS_POSITIVE],
                '2_0_0'          => ['result' => self::RULE_INVALID,'is_positive' => self::IS_UNKNOWN],
                '2_1_0'          => ['result' => self::RULE_SINGLE_POSITIVE,'is_positive' => self::IS_POSITIVE],
                '2_2_0'          => ['result' => self::RULE_INVALID,'is_positive' => self::IS_UNKNOWN],
                '2_-1_0'         => ['result' => self::RULE_INVALID,'is_positive' => self::IS_UNKNOWN],
                '-1_0_0'         => ['result' => self::RULE_WARNING,'is_positive' => self::IS_UNKNOWN],
                '-1_1_0'         => ['result' => self::RULE_WARNING,'is_positive' => self::IS_UNKNOWN],
                '-1_2_0'         => ['result' => self::RULE_WARNING,'is_positive' => self::IS_UNKNOWN],
                '-1_-1_0'        => ['result' => self::RULE_WARNING,'is_positive' => self::IS_UNKNOWN],
                '0_0_1'          => ['result' => self::RULE_NEGATIVE,'is_positive' => self::IS_NEGATIVE],
                '0_1_1'          => ['result' => self::RULE_SINGLE_POSITIVE,'is_positive' => self::IS_POSITIVE],
                '0_2_1'          => ['result' => self::RULE_NEGATIVE,'is_positive' => self::IS_NEGATIVE],
                '0_-1_1'         => ['result' => self::RULE_WARNING,'is_positive' => self::IS_UNKNOWN],
                '1_0_1'          => ['result' => self::RULE_SINGLE_POSITIVE,'is_positive' => self::IS_POSITIVE],
                '1_1_1'          => ['result' => self::RULE_POSITIVE,'is_positive' => self::IS_POSITIVE],
                '1_2_1'          => ['result' => self::RULE_POSITIVE,'is_positive' => self::IS_POSITIVE],
                '1_-1_1'         => ['result' => self::RULE_SINGLE_POSITIVE,'is_positive' => self::IS_POSITIVE],
                '2_0_1'          => ['result' => self::RULE_NEGATIVE,'is_positive' => self::IS_NEGATIVE],
                '2_1_1'          => ['result' => self::RULE_SINGLE_POSITIVE,'is_positive' => self::IS_POSITIVE],
                '2_2_1'          => ['result' => self::RULE_NEGATIVE,'is_positive' => self::IS_NEGATIVE],
                '2_-1_1'         => ['result' => self::RULE_WARNING,'is_positive' => self::IS_UNKNOWN],
                '-1_0_1'         => ['result' => self::RULE_WARNING,'is_positive' => self::IS_UNKNOWN],
                '-1_1_1'         => ['result' => self::RULE_WARNING,'is_positive' => self::IS_UNKNOWN],
                '-1_2_1'         => ['result' => self::RULE_WARNING,'is_positive' => self::IS_UNKNOWN],
                '-1_-1_1'        => ['result' => self::RULE_WARNING,'is_positive' => self::IS_UNKNOWN],
            ]
        ]
    ];

    // 使用静态方法来调用
    public static function check($ORF1ab='', $N='', $IC='', $ruleset='BS_1')
    {
        //组合相应的编号形成rulecode，在规则列表中查找相应的结果
        $ORF1ab = strtoupper(trim($ORF1ab));
        $N      = strtoupper(trim($N));
        $IC     = strtoupper(trim($IC));

        $NOCT_count = 0;
        $rule_code_ORF1ab = '';
        switch ($ORF1ab) {
            case '':
                $rule_code_ORF1ab = '0';
                break;
            case self::NOCT:
                $rule_code_ORF1ab = '0';
                $NOCT_count += 1;
                break;
            case floatval($ORF1ab)>self::$rule[$ruleset]['range'][self::ORF1AB][0]:
                $rule_code_ORF1ab = '2';
                break;
            case floatval($ORF1ab)<self::$rule[$ruleset]['range'][self::ORF1AB][1]:
                $rule_code_ORF1ab = '-1';
                break;
            default:
                $rule_code_ORF1ab = '1';
        }

        $rule_code_N = '';
        switch ($N) {
            case '':
                $rule_code_N = '0';
                break;
            case self::NOCT:
                $rule_code_N = '0';
                $NOCT_count += 1;
                break;
            case floatval($N)>self::$rule[$ruleset]['range'][self::N][0]:
                $rule_code_N = '2';
                break;
            case floatval($N)<self::$rule[$ruleset]['range'][self::N][1]:
                $rule_code_N = '-1';
                break;
            default:
                $rule_code_N = '1';
        }

        $rule_code_IC = '';
        switch ($IC) {
            case '':
                $rule_code_IC = '0';
                break;
            case self::NOCT:
                $rule_code_IC  = '0';
                $NOCT_count   += 1;
                break;
            case floatval($IC)>self::$rule[$ruleset]['range'][self::IC][0]:
                $rule_code_IC  = '0';
                $NOCT_count   += 1;
                break;
            default:
                $rule_code_IC = '1';
        }
        if ($NOCT_count>=3) {
            $rule_code = 'NOCT_NOCT_NOCT';
        } else {
            $rule_code = $rule_code_ORF1ab .'_'.$rule_code_N.'_'.$rule_code_IC;
        }

        $ret_default=[
            'rule_code'   => $rule_code,
            'result'      => self::RULE_NOT_FIND,
            'is_positive' => self::IS_UNKNOWN,
            'state'       => self::UNKNOWN,
        ];
        $find_result = self::$rule[$ruleset]['data'][$rule_code]??$ret_default;
        $state       = self::UNKNOWN;
        switch ($find_result['is_positive']) {
            case self::IS_NEGATIVE:
                $state = self::NEGATIVE;
                break;
            case self::IS_POSITIVE:
                $state = self::POSITIVE;
                break;
            case self::IS_UNKNOWN:
                $state = self::UNKNOWN;
                break;
        }
        $find_result['state']     = $state;
        $find_result['rule_code'] = $rule_code;

        return $find_result;
    }
}

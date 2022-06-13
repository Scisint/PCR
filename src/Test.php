<?php
namespace Scisint\PCR;
require_once "Pcr.php";
use Scisint\PCR\Pcr;

//  test ----
/*
A12	ORF1ab	35.15
A12	N 	38.03
A12	IC	25.06
*/
var_dump(Pcr::check('36.39','36.81','26.10','临界阳性对照'));

/*
F6	ORF1ab	NoCt
F6	N 	NoCt
F6	IC	36.44
*/
var_dump(Pcr::check('NoCt','NoCt',36.44));


/*
G2	ORF1ab	NoCt
G2	N 	NoCt
G2	IC	38.30
*/
var_dump(Pcr::check('NoCt','NoCt',38.30));

/*
E12	ORF1ab	35.74
E12	N 	35.50
E12	IC	25.04
*/
var_dump(Pcr::check('35.74','35.50',25.04));


/*
A1	ORF1ab	NoCt
A1	N 	NoCt
A1	IC	26.61
*/
var_dump(Pcr::check('NoCt','NoCt',26.61));


/*
F1	ORF1ab	NoCt
F1	N 	NoCt
F1	IC	26.26
*/
var_dump(Pcr::check('NoCt','NoCt','26.26'));

var_dump(Pcr::check('37','26','26.10','待测样品'));

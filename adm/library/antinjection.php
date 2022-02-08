<?php

function anti_injection($sql){
    $str_in = '"!@#$%#&*()-+={[}]/?;:,\\\'<>°ºª';
    $str_out = '';
    $sql_str = strtr($sql, $str_in, $str_out);
    $sql_trim = trim($sql_str);
    $sql_strip = strip_tags($sql_trim);
    $sql_cot = (get_magic_quotes_gpc()) ? $sql_strip : addslashes($sql_strip);
    return $sql_cot;
}

function vdados ($dados){
    $string = array_map('strip_tags', $dados);
    if(in_array('', $string)){
    	return false;
    } else {
    	return $dados;
    }
}

function validaEmail($email){
    $condicao = '/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/';
    if(preg_match($condicao, $email)){
        return true;
    }else{
        return false;
    }
}
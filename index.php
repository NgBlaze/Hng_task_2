<?php

header('Access-Control-Allow-Origin: *');

header('Content-Type: application/json; charset=utf-8');

header('Access-Control-Allow-Methods: POST');

header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

$output = array();

$input = json_decode(file_get_contents("php://input"), true);
if (isset($input['operation_type']) && isset($input['x']) && isset($input['y']) && gettype($input['x']) == 'integer' && gettype($input['y']) == 'integer') {
    http_response_code(200);

    extract($input);
    $string = $input['operation_type'];
    $opstring = strtolower($input['operation_type']);
    $variables = array();
    $sign = "";

    for ($i = 0; $i < strlen($string); $i++) {
        if (in_array(strtolower($string[$i]), array("x", "y"))) {
            array_push($variables, $string[$i]);
        }
        if (in_array($string[$i], array("+", "-", "*"))) {
            $sign = $string[$i];
        }
    }


    if (strrpos($opstring, "add") || strrpos($opstring, "plus") || strrpos($opstring, "sum")) {
        $sign = "+";
    }
    if (strrpos($opstring, "subtract") || strrpos($opstring, "minus") || strrpos($opstring, "remove") || strrpos($opstring, "takeaway")) {
        $sign = "-";
    }
    if (strrpos($opstring, "multiply") || strrpos($opstring, "product") || strrpos($opstring, "times")) {
        $sign = "*";
    }

    if ($string == "addition") {
        $sign = "+";
    }
    if ($string == "subtraction") {
        $sign = "-";
    }
    if ($string == "multiplication") {
        $sign = "*";
    }


    if (!strrpos($opstring, "from")) {
        if (!in_array("x", explode(" ", $string)) && !in_array("y", explode(" ", $string))) {
            $num1 = "x";
            $num2 = "y";
        } else {
            $num1 = $variables[0];
            $num2 = $variables[1];
        }
    } else {
        $num1 = $variables[1];
        $num2 = $variables[0];
    }

    if ($sign == '+') {
        $result = intval(${$num1}) + intval(${$num2});
        $operation = "addition";
    } elseif ($sign == '-') {
        $result = intval(${$num1}) - intval(${$num2});
        $operation = "subtraction";
    } elseif ($sign == '*') {
        $result = intval(${$num1}) * intval(${$num2});
        $operation = "multiplication";
    } else {
        $result = "Your input was not valid";
    }

    $output["slackUsername"] = "Goons";
    $output["operation_type"] = $operation;
    $output["result"] = $result;
}
echo json_encode($output);

<?php

    function getJsonResponse($status, $errorMessage, $returnData = null){
        return json_encode(array(
            'status' => $status,
            'errorMessage' => $errorMessage,
            'returnData' => $returnData,
        ));
    }

    function toJson($data = null){
        return json_encode($data);
    }

    function jsonToArray($jsondata = null){
        return json_decode($jsondata, true);
    }
?>
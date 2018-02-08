<?php

require "vendor/autoload.php";


/**
 * Valida e higieniza os valores do array
 *
 * @param array $aDados - Array a ser processado, por exemplo, $_POST ou $_GET
 * @param array $aValidacoes - Validações a serem aplicadas em cada campo
 * @param array $aFiltros - Filtros a serem aplicados em cada campo
 *
 * @return Object
 */
function fHigienizaInput($aDados, $aValidacoes = [], $aFiltros = []) {
  $oGump = new GUMP();

 // $aDados = $oGump->sanitize($aDados);

  if (!empty($aValidacoes))
    $oGump->validation_rules($aValidacoes);

  if (!empty($aFiltros))
    $oGump->filter_rules($aFiltros);

  $oRun = $oGump->run($aDados);
  $oReturn = new StdClass();

  if ($oRun === false) {
    $oReturn->bOk = false;
    $oReturn->aErros = $oGump->get_errors_array();
  } else {
    $oReturn->bOk = true;
    $oReturn->aResultado = $oRun;
  }

  return $oReturn;
}

/**
 * Valida e higieniza a super variável $_POST. Retorna um
 * boleano conforme o sucesso do processo
 *
 * @param array $aValidacoes - Validações a serem aplicadas em cada campo
 * @param array $aFiltros - Filtros a serem aplicados em cada campo
 * @return boolean
 */
function fHigienizaPost($aValidacoes = [], $aFiltros = []) {

  $oRet = fHigienizaInput($_POST, $aValidacoes, $aFiltros);

  if ($oRet->bOk)
    $_POST = $oRet->aResultado;
  else
    $_POST = $oRet->aErros;

  return $oRet->bOk;
}

/**
 * Valida e higieniza a super variável $_GET. Retorna um
 * boleano conforme o sucesso do processo
 *
 * @param array $aValidacoes - Validações a serem aplicadas em cada campo
 * @param array $aFiltros - Filtros a serem aplicados em cada campo
 * @return boolean
 */
function fHigienizaGet($aValidacoes = [], $aFiltros = []) {

  $oRet = fHigienizaInput($_GET, $aValidacoes, $aFiltros);

  if ($oRet->bOk)
    $_GET = $oRet->aResultado;
  else
    $_GET = $oRet->aErros;

  return $oRet->bOk;
}

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

/**
 * Aplica o 'escape' na string e retorna o
 * resultado
 *
 * @param string $sConteudo - Conteúdo
 * @param string $sTipo - Tipo do conteúdo: html, css, js, url ou attr
 *
 * @return string
 */
function fE($sConteudo, $sTipo = 'html') {

  $oEscaper = new Zend\Escaper\Escaper('utf-8');

  switch (strtolower($sTipo)) {
    case 'css':
      return $oEscaper->escapeCss($sConteudo);
    break;
    case 'url':
      return $oEscaper->escapeUrl($sConteudo);
    break;
    case 'attr':
      return $oEscaper->escapeHtmlAttr($sConteudo);
    break;
    case 'js':
      return $oEscaper->escapeJs($sConteudo);
    break;
    default:
      return $oEscaper->escapeHtml($sConteudo);
    break;
  }
}

/**
 * Gera e retorna um input html 5 com um
 * token para controle CSRF
 *
 * @param string $sTokenId - Identificação do token
 *
 * @return string - Input pronto para uso
 */
function fCsrfInputBuilder($sTokenId) {

  if (session_status() == PHP_SESSION_NONE) {
    throw new Exception('Nenhuma sessão iniciada!');
  }

  $oManager = new Symfony\Component\Security\Csrf\CsrfTokenManager();

  $sInput = '<input type="hidden" name="_csrf" value="%s">';

  return sprintf($sInput, $oManager->getToken($sTokenId));
}

/**
 * Retorna se o token csrf é válido
 *
 * @param string $sTokenId - Identificação do token
 * @param array $aRecebido - Array, do método HTTP, com o token recebido. Exemplos: $_POST e $_GET
 * @param boolean $bRemover - Se a função deve já remover o token do armazenamento
 *
 * @return boolean - Se o token é válido
 */
function fCsrfTokenIsValid($sTokenId, $aRecebido, $bRemover = true) {

  if (empty($aRecebido['_csrf']))
    return false;

  if (session_status() == PHP_SESSION_NONE)
    throw new Exception('Nenhuma sessão iniciada!');

  $oManager = new Symfony\Component\Security\Csrf\CsrfTokenManager();

  $oToken = new Symfony\Component\Security\Csrf\CsrfToken($sTokenId, $aRecebido['_csrf']);

  $bIsValid = $oManager->isTokenValid($oToken);

  if ($bRemover)
    $oManager->removeToken($oToken);

  return $bIsValid;
}

/**
 * Retorna se o token csrf, via $_GET, é válido
 *
 * @param string $sTokenId - Identificação do token
 * @param boolean $bRemover - Se a função deve já remover o token do armazenamento
 *
 * @return boolean - Se o token é válido
 */
function fCsrfTokenIsValidGet($sTokenId, $bRemover = true) {
  return fCsrfTokenIsValid($sTokenId, $_GET, $bRemover);
}

/**
 * Retorna se o token csrf, via $_POST, é válido
 *
 * @param string $sTokenId - Identificação do token
 * @param boolean $bRemover - Se a função deve já remover o token do armazenamento
 *
 * @return boolean - Se o token é válido
 */
function fCsrfTokenIsValidPost($sTokenId, $bRemover = true) {
  return fCsrfTokenIsValid($sTokenId, $_POST, $bRemover);
}

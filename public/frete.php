<?php
require_once '../lib/PayPalFreteFacil.php';

$altura = 2;
$largura = 15;
$comprimento = 30;
$peso = 1;

$items = isset( $_POST[ 'items' ] ) ? (int) $_POST[ 'items' ] : 1;

if ( $items < 1 ) {
	$items = 0;
}

$std = new stdClass();
$std->message = '';
$std->success = false;
$std->frete = 0;

if ( isset( $_POST[ 'cep' ] ) && preg_match( '/^\d{4,5}-?\d{3}$/', $_POST[ 'cep' ] ) ) {
	$ffPayPal = new PayPalFreteFacil();
	$ffPayPal->setCepOrigem( '04094-050' );
	$ffPayPal->setCepDestino( $_POST[ 'cep' ] );
	$ffPayPal->setAltura( $altura * $items );
	$ffPayPal->setLargura( $largura );
	$ffPayPal->setProfundidade( $comprimento );
	$ffPayPal->setPeso( $peso * $items );
	
	$std->frete = $ffPayPal->getPreco();
	
	if ( $std->frete <= 0 ) {
		$std->message = 'Falha ao calcular o frete';
	} else {
		$std->success = true;
	}
} else {
	$std->message = 'CEP inválido';
}

echo json_encode( $std );
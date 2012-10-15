<?php
/**
 * Wrapper para consumo do webservice PayPal Frete Fácil.
 */
class PayPalFreteFacil {
	/**
	 * Caminho para o webservice do PayPal Frete Fácil
	 */
	const ADDRESS = 'https://ff.paypal-brasil.com.br/FretesPayPalWS/WSFretesPayPal';
	
	/**
	 * @var			stdClass
	 * @property	integer $altura Altura da embalagem
	 * @property	string $cepDestino CEP de destino
	 * @property	string $cepOrigem CEP de origem
	 * @property	integer $largura Largura da embalagem
	 * @property	string $peso Peso da embalagem
	 * @property	integer $profundidade Profundidade da embalagem
	 */
	private $request;
	
	/**
	 * O PayPal Frete Fácil é um serviço onde você tem desconto a partir de 30%
	 * no valor do frete, em relação ao valor de balcão do SEDEX dos Correios,
	 * para vendas feitas pelo PayPal.
	 * @param	string $cepOrigem CEP de Origem.
	 * @param	string $cepDestino CEP de Destino.
	 */
	public function __construct( $cepOrigem = null , $cepDestino = null ) {
		$this->request = new stdClass();
		$this->request->altura = 0;
		$this->request->largura = 0;
		$this->request->peso = null;
		$this->request->profundidade = 0;
		
		$this->setCepDestino( $cepDestino );
		$this->setCepOrigem( $cepOrigem );
	}
	
	/**
	 * Calcula o valor do frete para os dados informados.
	 * @return	float O valor do frete para os dados informados.
	 * @throws	RuntimeException Caso não tenha sido possível consumir
	 * 			o serviço de cálculo de frete.
	 */
	public function getPreco() {
		try {
			return $this->getSoapClient()->getPreco( $this->request )->{'return'};
		} catch ( Exception $e ) {
			throw new RuntimeException( 'Falha ao consumir o webservice' , $e->getCode() , $e );
		}
	}
	
	/**
	 * @return	SoapClient
	 */
	protected function getSoapClient() {
		//Para que o código de integração funcione adequadamente,
		//as seguintes dependências devem ser resolvidas:
		//
		//OpenSSL - http://www.php.net/manual/pt_BR/openssl.installation.php
		//PHP Soap - http://www.php.net/manual/en/soap.installation.php
		//
		//A extensão PHP Soap depende ainda da libxml, que também
		//deve estar disponível no sistema.
		$client = new SoapClient( PayPalFreteFacil::ADDRESS . '?wsdl' , array(
			'trace'			=> true,
			'exceptions'	=> true,
			'style'			=> SOAP_DOCUMENT,
			'use'			=> SOAP_LITERAL,
			'soap_version'	=> SOAP_1_1,
			'location'		=> PayPalFreteFacil::ADDRESS,
			'encoding'		=> 'UTF-8'
		) );
		
		return $client;
	}

	/**
	 * Define a altura da embalagem.
	 * @param	integer $altura
	 */
	public function setAltura( $altura ) {
		$this->request->altura = (int) $altura;
	}

	/**
	 * Define o CEP de destino.
	 * @param	string $cepDestino
	 */
	public function setCepDestino( $cepDestino ) {
		$this->request->cepDestino = $cepDestino;
	}

	/**
	 * Define o CEP de origem.
	 * @param	string $cepOrigem
	 */
	public function setCepOrigem( $cepOrigem ) {
		$this->request->cepOrigem = $cepOrigem;
	}

	/**
	 * Define a largura da embalagem.
	 * @param	integer $largura
	 */
	public function setLargura( $largura ) {
		$this->request->largura = (int) $largura;
	}

	/**
	 * Define o peso da embalagem.
	 * @param	string $peso
	 */
	public function setPeso( $peso ) {
		$this->request->peso = $peso;
	}

	/**
	 * Define a profundidade da embalagem.
	 * @param	integer $profundidade
	 */
	public function setProfundidade( $profundidade ) {
		$this->request->profundidade = (int) $profundidade;
	}
}
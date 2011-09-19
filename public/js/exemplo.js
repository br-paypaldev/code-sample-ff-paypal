/**
 * Calcula o total do carrinho 
 */
function cartTotal() {
	var total = 0;
	
	$('td.total span').each(function(){
		total += parseFloat($(this).text());
	});
	
	total = Math.round( total * 100 ) / 100;
	
	$('td.cart-total span').text(total);
}

$(function(){
	/**
	 * Observa por mudanças no campos de quantidade e recalcula
	 * os preços totais de cada produto e o total do carrinho.
	 */
	$('tbody tr td input').change(function(){
		try {
			var tr = $(this).parent().parent();
			var val = parseInt( $(this).val() );
			var total = parseFloat(tr.find('td.price span').text())*val;
			
			tr.find('td.total span').html(total);
			$('tr.frete td span').text( '0.00' );
			
			cartTotal();
		} catch ( e ){
			$(this).val(1);
		}
	});
	
	/**
	 * Observa o clique no botão de cálculo de frete e atualiza o total
	 * do carrinho com o valor do frete.
	 */
	$('#calccep').click(function(){
		var cep = $('#cep').val();
		var items = 0;
		var button = $(this);
		
		/**
		 * Verifica o total de itens do carrinho
		 */
		$('tbody tr td input').each(function(){
			items += parseInt($(this).val());
		});
		
		/**
		 * Faz a requisição ajax ao servidor para o cálculo do frete
		 */
		$.ajax( {
			url		: 'frete.php',
			type	: 'post',
			data	: { cep : cep , items : items },
			dataType: 'json',
			success	: function( resp ) {
				if ( resp.success ) {
					$('tr.frete td span').text( resp.frete );
					
					cartTotal();
				} else {
					alert( resp.message );
				}
			}
		} );
	});
});
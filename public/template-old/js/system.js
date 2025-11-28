var base_url = window.location.origin + "/";

$('.datafmtbr').mask('99/99/9999');
$('.datafmtbr').datepicker({
  autoclose: true,
  format: 'dd.mm.yyyy',
  language: 'pt-BR'
});
$('.datafmtnf').mask('99/9999');
$('.datafmtnf').datepicker({
  autoclose: true,
  format: 'mm.yyyy',
  language: 'pt-BR'
});

$('.data').mask('99/99/9999');
$('.horafmtbr').mask('99:99');


$('.competencia').mask('99/9999');

$('.mac').mask('##:##:##:##:##:##');
$('.ip').mask('999.999.999.999');
$('.cpf').mask('999.999.999-99');
$('.cnpj').mask('99.999.999/9999-99');
$('.cep').mask('99999-999');
$('.placa').mask('AAA-9999');

$('.telefone').mask('(99)99999-9999');
$('.horario').mask('99:99');
$('.tel').mask('(99)99999-9999');
$('.percentual').mask('99.99');

$('.dinheiro').maskMoney({

  prefix: 'R$ ',

  allowNegative: true,

  thousands: '',

  decimal: ',',

  affixesStay: false

});
$('.metragem').maskMoney({

  prefix: '',

  allowNegative: true,

  thousands: '',

  decimal: '.',

  affixesStay: false

});



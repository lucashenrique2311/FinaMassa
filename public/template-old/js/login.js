/* Funções da tabela Configuracoes/Funcionario */
window.onload = function () {
  // Exemplo de uso

  document.addEventListener('gesturestart', function (e) {
    e.preventDefault();
});

  $('#username').change(function () {

    var username = $(this);
    $.ajax({
      type: "POST",
      url: '/Login/getEscolas',
      data: {
        'usuario': username.val()
      },
      dataType: 'json',
      success: function (data) {
        console.log(data)
        var select = $('#profissional_escola');
        select.empty(); // Limpa as opções existentes

        select.append('<option value="">Selecione uma escola</option>'); // Adiciona a opção padrão

        // Percorre o array de escolas retornado e adiciona cada uma ao select
        $.each(data, function (index, escola) {
          select.append($('<option></option>').attr('value', escola.ID_ESCOLA).text(escola.ESCOLA));
        });
      }
    });

  });

}

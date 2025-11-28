/* Funções da tabela Configuracoes/Funcionario */
window.onload = function () {
    // Exemplo de uso
  
  var table = $('#usuario').DataTable({
    "language": {
      "sEmptyTable": "Nenhum registro encontrado",
      "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
      "sInfoFiltered": "(Filtrados de _MAX_ registros)",
      "sInfoPostFix": "",
      "sInfoThousands": ".",
      "sLengthMenu": "_MENU_ resultados por página",
      "sLoadingRecords": "Carregando...",
      "sProcessing": "Processando...",
      "sZeroRecords": "Nenhum registro encontrado",
      "sSearch": "Pesquisar",
      "oPaginate": {
        "sNext": "Próximo",
        "sPrevious": "Anterior",
        "sFirst": "Primeiro",
        "sLast": "Último"
      },
      "oAria": {
        "sSortAscending": ": Ordenar colunas de forma ascendente",
        "sSortDescending": ": Ordenar colunas de forma descendente"
      },
      "buttons": {
        "selectAll": "Marcar todos",
        "selectNone": "Desmarcar",
        "print": "Imprimir"
      },
      "select": {
        "rows": {
            _: "Selecionado %d linhas",
            0: "Clique em uma linha para selecionar",
            1: "Apenas 1 linha selecionada"
        }
    }
    },
    responsive: true,
    searchDelay: 500,
    ordering: true,
    dom: 'Bfrtip',
    select: true,
    buttons: [
      'csv', 'excel', 'pdf', 'print'
    ],

    "lengthMenu": [ [100,200,500,1000, 10000, 100000], [100,200,500,1000, 10000, "Todos"] ],
  });

  $('#gravar').click(function () {
    Swal.fire({
        title: "Deseja salvar?",
        text: "Certifique-se de ter verificado todos os dados antes de efetuar a ação!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, pode salvar!"
      }).then((result) => {
        if (result.isConfirmed) {
            $('#frmUsuario').submit();
        }else{
            swal("Cancelado", "Operação cancelada! Dados não gravados", "error");
        }
    });
});

$('#cep').on('change', function(){
  //Nova variável "cep" somente com dígitos.
  var cep = $(this).val().replace(/\D/g, '');

  //Verifica se campo cep possui valor informado.
  if (cep != "") {

    //Expressão regular para validar o CEP.
    var validacep = /^[0-9]{8}$/;

    //Valida o formato do CEP.
    if (validacep.test(cep)) {

      //Preenche os campos com "..." enquanto consulta webservice.
      $("#endereco").val("...");
      $("#bairro").val("...");
      $("#cidade").val("...");
      $("#uf").val("...");

      //Consulta o webservice viacep.com.br/
      $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

        if (!("erro" in dados)) {
          //Atualiza os campos com os valores da consulta.
          $("#endereco").val(dados.logradouro);
          $("#bairro").val(dados.bairro);
          $("#cidade").val(dados.localidade);
          $("#uf").val(dados.uf);
        } //end if.

      });
    } //end if.
  } //end if.
})
  
    $('#limpafrmpesquisa').click(function () {
      console.log('teste');
      $("#frm_pesquisa")[0].reset();
      $('#frm_pesquisa input').val(""); //coloca todos valores de todos inputs do form como vazio
      $('#frm_pesquisa select').val(""); //coloca todos valores de todos inputs do form como vazio
      $(":checkbox").prop('checked', false);
      $(':checkbox').find('checked').remove();
    });
  
    $('#enviafrmpesquisa').click(function () {
      $("#frm_pesquisa")[0].submit();
    });


  
  }
  
/* Funções da tabela Configuracoes/Funcionario */
window.onload = function () {

  $('form').on('keydown', function (event) {
    if (event.key === 'Enter') {
      event.preventDefault();
    }
  });
  // Exemplo de uso

  var table = $('#tabela_padrao_datatable').DataTable({
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

    "lengthMenu": [[100, 200, 500, 1000, 10000, 100000], [100, 200, 500, 1000, 10000, "Todos"]],
  });

  $('#profissional_cep').on('change', function () {
    //Nova variável "cep" somente com dígitos.
    var cep = $(this).val().replace(/\D/g, '');

    //Verifica se campo cep possui valor informado.
    if (cep != "") {

      //Expressão regular para validar o CEP.
      var validacep = /^[0-9]{8}$/;

      //Valida o formato do CEP.
      if (validacep.test(cep)) {

        //Preenche os campos com "..." enquanto consulta webservice.
        $("#profissional_endereco").val("...");
        $("#profissional_bairro").val("...");
        $("#profissional_cidade").val("...");
        $("#profissional_estado").val("...");

        //Consulta o webservice viacep.com.br/
        $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

          if (!("erro" in dados)) {
            console.log(dados)
            //Atualiza os campos com os valores da consulta.
            $("#profissional_endereco").val(dados.logradouro);
            $("#profissional_bairro").val(dados.bairro);
            $("#profissional_cidade").val(dados.localidade);
            $("#profissional_estado").val(dados.uf);
          } //end if.

        });
      } //end if.
    } //end if.
  })

  $('#aluno_cep').on('change', function () {
    //Nova variável "cep" somente com dígitos.
    var cep = $(this).val().replace(/\D/g, '');

    //Verifica se campo cep possui valor informado.
    if (cep != "") {

      //Expressão regular para validar o CEP.
      var validacep = /^[0-9]{8}$/;

      //Valida o formato do CEP.
      if (validacep.test(cep)) {

        //Preenche os campos com "..." enquanto consulta webservice.
        $("#aluno_endereco").val("...");
        $("#aluno_bairro").val("...");
        $("#aluno_cidade").val("...");
        $("#aluno_estado").val("...");

        //Consulta o webservice viacep.com.br/
        $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

          if (!("erro" in dados)) {
            console.log(dados)
            //Atualiza os campos com os valores da consulta.
            $("#aluno_endereco").val(dados.logradouro);
            $("#aluno_bairro").val(dados.bairro);
            $("#aluno_cidade").val(dados.localidade);
            $("#aluno_estado").val(dados.uf);
          } //end if.

        });
      } //end if.
    } //end if.
  })

  // if(document.querySelector('form[name="profissional"]').addEventListener('submit', function(event) {
  //   var escolaridade = document.querySelector('input[name="profissional_escolaridade"]:checked');
  //   if (escolaridade && escolaridade.value === 'S') {
  //       var grauAcademico = document.querySelector('input[name="profissional_nivel_grau_academico"]:checked');
  //       if (!grauAcademico) {
  //           alert('Por favor, selecione o Nível / Grau Acadêmico.');
  //           event.preventDefault(); // Impede o envio do formulário
  //       }
  //   }
  // }));

  $('#cep').on('change', function () {
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
        $("#estado").val("...");

        //Consulta o webservice viacep.com.br/
        $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

          if (!("erro" in dados)) {
            console.log(dados)
            //Atualiza os campos com os valores da consulta.
            $("#endereco").val(dados.logradouro);
            $("#bairro").val(dados.bairro);
            $("#cidade").val(dados.localidade);
            $("#estado").val(dados.uf);
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
        $('#frm').submit();
      } else {
        swal("Cancelado", "Operação cancelada! Dados não gravados", "error");
      }
    });
  });

  document.addEventListener('gesturestart', function (e) {
    e.preventDefault();
});

/*   $('#gravar_acompanhamento').click(function () {
    Swal.fire({
      title: "Deseja salvar acompanhamento?",
      text: "Certifique-se de ter verificado todos os dados antes de efetuar a ação!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sim, pode salvar!"
    }).then((result) => {
      if (result.isConfirmed) {
        $('#frm').submit();
      } else {
        swal("Cancelado", "Operação cancelada! Dados não gravados", "error");
      }
    });
  }); */

  $('#profissional_estado_naturalidade').on('change', function () {
    var uf = $(this).val();
    var savedCityId = $('#profissional_naturalidade_hidden').val();

    $.ajax({
      type: "GET",
      url: 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' + uf + '/municipios',
      dataType: 'json',
      success: function (data) {
        if (!data || !Array.isArray(data)) return; // Verifica se 'data' é um array

        var select = $('#profissional_naturalidade');
        select.empty(); // Limpa as opções existentes

        select.append('<option value="">Selecione uma cidade</option>'); // Adiciona a opção padrão

        // Percorre o array de cidades retornado e adiciona cada uma ao select
        $.each(data, function (index, cidade) {
          var option = $('<option></option>').attr('value', cidade.id).text(cidade.nome);
          select.append(option);
        });

        // Seleciona a cidade salva, se existir
        if (savedCityId) {
          select.val(savedCityId);
        }
      }
    });
  }).trigger('change');


  $('#aluno_estado_naturalidade').on('change', function () {
    var uf = $(this).val();
    var savedCityId = $('#aluno_naturalidade_hidden').val();

    console.log(savedCityId)
    $.ajax({
      type: "GET",
      url: 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' + uf + '/municipios',
      dataType: 'json',
      success: function (data) {
        if (!data || !Array.isArray(data)) return; // Verifica se 'data' é um array

        console.log(data);
        var select = $('#aluno_naturalidade');
        select.empty(); // Limpa as opções existentes

        select.append('<option value="">Selecione uma cidade</option>'); // Adiciona a opção padrão

        // Percorre o array de cidades retornado e adiciona cada uma ao select
        $.each(data, function (index, cidade) {
          var option = $('<option></option>').attr('value', cidade.id).text(cidade.nome);
          select.append(option);
        });

        // Seleciona a cidade salva, se existir
        if (savedCityId) {
          select.val(savedCityId);
        }
      }
    });
  }).trigger('change');

  $('#profissional_data_nascimento').on('change', function () {
    var data_nascimento = $(this).val();

    var hoje = new Date();
    var dataNasc = new Date(data_nascimento);
    var idade = hoje.getFullYear() - dataNasc.getFullYear();
    var diferencaMes = hoje.getMonth() - dataNasc.getMonth();

    if (diferencaMes < 0 || (diferencaMes === 0 && hoje.getDate() < dataNasc.getDate())) {
      idade--;
    }
    $('#profissional_idade').val(idade);

  })

  $('#ano_vinculo').on('change', function () {
    var ano = $(this).val();

    $.ajax({
      type: "GET",
      url: base_url+'Turma/getTurmaAno',
      dataType: 'json',
      data:{
        'ano': ano
      },
      success: function (data) {

        var select = $('#turma_vinculo');
        select.empty(); // Limpa as opções existentes

        select.append('<option value="">Selecione uma turma</option>'); // Adiciona a opção padrão

        // Percorre o array de cidades retornado e adiciona cada uma ao select
        $.each(data, function (index, turma) {
          var option = $('<option></option>').attr('value', turma.ID_TURMA).text(turma.NOME_TURMA);
          select.append(option);
        });
      }
    });
  }).trigger('change');

  $('#aluno_data_nascimento').on('change', function () {
    var data_nascimento = $(this).val();

    var hoje = new Date();
    var dataNasc = new Date(data_nascimento);
    var idade = hoje.getFullYear() - dataNasc.getFullYear();
    var diferencaMes = hoje.getMonth() - dataNasc.getMonth();

    if (diferencaMes < 0 || (diferencaMes === 0 && hoje.getDate() < dataNasc.getDate())) {
      idade--;
    }
    $('#aluno_idade').val(idade);

  })

  $(".divCursoSuperior").hide();
  var profissional_escolaridade = $('input[name="profissional_escolaridade"]:checked').val();


  if (profissional_escolaridade == "S") {
    $(".divCursoSuperior").show();
  }

  $('input[name="profissional_escolaridade"]').change(function () {
    var profissional_escolaridade = $('input[name="profissional_escolaridade"]:checked').val();
    if (profissional_escolaridade == "S") {
      $(".divCursoSuperior").show();
    } else {
      $(".divCursoSuperior").hide();
    }
  });


  $(".divHora").hide();
  var profissional_intervalo = $('input[name="profissional_intervalo"]:checked').val();


  if (profissional_intervalo == "S") {
    $(".divHora").show();
  }

  $('input[name="profissional_intervalo"]').change(function () {
    var profissional_intervalo = $('input[name="profissional_intervalo"]:checked').val();
    if (profissional_intervalo == "S") {
      $(".divHora").show();
    } else {
      $(".divHora").hide();
    }
  });


  $(".divDeficiencia").hide();
  var aluno_possui_deficiencia = $('#aluno_possui_deficiencia').val();


  if (aluno_possui_deficiencia == "S") {
    $(".divDeficiencia").show();
  }

  $('#aluno_possui_deficiencia').change(function () {
    var aluno_possui_deficiencia = $('#aluno_possui_deficiencia').val();
    if (aluno_possui_deficiencia == "S") {
      $(".divDeficiencia").show();
    } else {
      $(".divDeficiencia").hide();
    }
  });


  $(".divTranstorno").hide();
  var aluno_possui_transtorno = $('#aluno_possui_transtorno').val();


  if (aluno_possui_transtorno == "S") {
    $(".divTranstorno").show();
  }

  $('#aluno_possui_transtorno').change(function () {
    var aluno_possui_transtorno = $('#aluno_possui_transtorno').val();
    if (aluno_possui_transtorno == "S") {
      $(".divTranstorno").show();
    } else {
      $(".divTranstorno").hide();
    }
  });

  $(".divDoencas").hide();
  var aluno_possui_doencas_cronicas = $('#aluno_possui_doencas_cronicas').val();


  if (aluno_possui_doencas_cronicas == "S") {
    $(".divDoencas").show();
  }

  $('#aluno_possui_doencas_cronicas').change(function () {
    var aluno_possui_doencas_cronicas = $('#aluno_possui_doencas_cronicas').val();
    if (aluno_possui_doencas_cronicas == "S") {
      $(".divDoencas").show();
    } else {
      $(".divDoencas").hide();
    }
  });
  /* 
  
    var aluno_filiacao_1 = $('#aluno_filiacao_1').val();
    var aluno_filiacao_2 = $('#aluno_filiacao_2').val();
  
    if (aluno_filiacao_1 == "" || aluno_filiacao_2 == "") {
      $(".divResponsaveis").show();
    } else {
      $(".divResponsaveis").hide();
    }
  
    $('#aluno_filiacao_1').change(function () {
      var aluno_filiacao_1 = $('#aluno_filiacao_1').val();
      if (aluno_filiacao_1 != "") {
        $(".divResponsaveis").hide();
      } else {
        $(".divResponsaveis").show();
      }
    });
  
    $('#aluno_filiacao_2').change(function () {
      var aluno_filiacao_2 = $('#aluno_filiacao_2').val();
      if (aluno_filiacao_2 != "") {
        $(".divResponsaveis").hide();
      } else {
        $(".divResponsaveis").show();
      }
    }); */

    $('.divResponsaveis').hide();
    $('#aluno_responsavel_legal').on('change', function(){
      if($(this).val() == "O"){
        $('.divResponsaveis').show();
      }else{
        $('.divResponsaveis').hide();
      }
    })

    if($('#aluno_responsavel_legal').val() == "O"){
      $('.divResponsaveis').show();
    }


  var username = $('#username').val();
  var profisisonal_escola_id = $('#profisisonal_escola_id').val();
  $.ajax({
    type: "POST",
    url: '/Login/getEscolas',
    data: {
      'usuario': username
    },
    dataType: 'json',
    success: function (data) {
      var select = $('#profissional_escola');
      select.empty(); // Limpa as opções existentes

      select.append('<option value="">Selecione uma escola</option>'); // Adiciona a opção padrão

      // Percorre o array de escolas retornado e adiciona cada uma ao select
      $.each(data, function (index, escola) {
        select.append($('<option></option>').attr('value', escola.ID_ESCOLA).text(escola.ESCOLA));
      });

      if (profisisonal_escola_id) {
        select.val(profisisonal_escola_id);
      }
    }
  });

  //JS do App

  // Evento de clique no botão Salvar
  $('.btn.btn-danger.mb-5').on('click', function() {
    // Capturando data, hora e descrição
    // Capturando data
    var data = $('input[type="date"]').val();

    if (!data) {
      $('#modal-danger .modal-body p').text('Por favor, preencha a data antes de salvar.');
      $('#modal-danger').modal('show');
      return; // Interrompe o processo de salvamento
  }
    var hora = $('input[name="profissional_horario_saida"]').val();
    var profissional_codigo = $('input[name="profissional_codigo"]').val();
    var descricao = $('#textarea').val();
    var turma = $('#turma').val();
    var tipo = $('#tipo').val();
    var id_ocorrencia = $('#id_ocorrencia').val();
    var id_chamada = $('#id_chamada').val();

    // Capturando os alunos e suas justificativas
    var alunos = [];
    var temFaltaSemJustificativa = false;

    $('input[type="checkbox"]').each(function() {
      var alunoId = $(this).attr('id').split('-')[1];
      if (alunoId !== undefined) {
          var status = $(this).is(':checked') ? "S" : "N";
          var justificativa = $(this).closest('.form-group').data('justificativa') || "";
          
          // Verifica se o aluno tem status "N" e não possui justificativa
          if (status === "N" && justificativa === "") {
              temFaltaSemJustificativa = true;
          }
  
          alunos.push({ id: alunoId, status: status, justificativa: justificativa });
      }
    });

    console.log(temFaltaSemJustificativa)

    if(temFaltaSemJustificativa){
      Swal.fire({
        title: "Atenção?",
        text: "Existem alunos que não possuem justificativa preenchida, deseja continuar?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, pode salvar!"
      }).then((result) => {
        if (result.isConfirmed) {
          if(tipo == "O"){
            $.ajax({
              type: "POST",
              url: '/Aplicativo/OcorrenciaInserir',
              data: {
                data: data,
                hora: hora,
                descricao: descricao,
                alunos: alunos,
                turma: turma,
                id_ocorrencia: id_ocorrencia
              },
              dataType: 'json',
              success: function (data) {
                if(data.msg == "success"){
      
                  Swal.fire({
                    title: "Sucesso!",
                    text: "Ocorrência salva!",
                    icon: "success",
                    confirmButtonColor: "#f75808",
                  }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                      window.location.href = '/Aplicativo/ListaOcorrencia/'+turma; // Substitua pela URL de destino
                    } else if (result.isDenied) {
                      window.location.href = '/Aplicativo/ListaOcorrencia/'+turma; // Substitua pela URL de destino
                    }
                  });
                }
              }
            });
          }else {
            $.ajax({
              type: "POST",
              url: '/Aplicativo/ChamadaInserir',
              data: {
                data: data,
                hora: hora,
                profissional_codigo: profissional_codigo,
                descricao: descricao,
                alunos: alunos,
                turma: turma,
                id_chamada: id_chamada
              },
              dataType: 'json',
              success: function (data) {
                if(data.msg == "success"){
                  Swal.fire({
                    title: "Sucesso!",
                    text: "Chamada salva!",
                    icon: "success",
                    confirmButtonColor: "#f75808",
                  }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                      window.location.href = '/Aplicativo/Chamada'; // Substitua pela URL de destino
                    } else if (result.isDenied) {
                      window.location.href = '/Aplicativo/Chamada'; // Substitua pela URL de destino
                    }
                  });
                }
              }
            });
          }
        }else{
            //swal("Cancelado", "Operação cancelada! Dados não gravados", "error");
        }
      });
    }else{
      if(tipo == "O"){
        $.ajax({
          type: "POST",
          url: '/Aplicativo/OcorrenciaInserir',
          data: {
            data: data,
            hora: hora,
            descricao: descricao,
            alunos: alunos,
            turma: turma,
            id_ocorrencia: id_ocorrencia
          },
          dataType: 'json',
          success: function (data) {
            if(data.msg == "success"){
  
              Swal.fire({
                title: "Sucesso!",
                text: "Ocorrência salva!",
                icon: "success",
                confirmButtonColor: "#f75808",
              }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                  window.location.href = '/Aplicativo/ListaOcorrencia/'+turma; // Substitua pela URL de destino
                } else if (result.isDenied) {
                  window.location.href = '/Aplicativo/ListaOcorrencia/'+turma; // Substitua pela URL de destino
                }
              });
            }
          }
        });
      }else {
        $.ajax({
          type: "POST",
          url: '/Aplicativo/ChamadaInserir',
          data: {
            data: data,
            hora: hora,
            profissional_codigo: profissional_codigo,
            descricao: descricao,
            alunos: alunos,
            turma: turma,
            id_chamada: id_chamada
          },
          dataType: 'json',
          success: function (data) {
            if(data.msg == "success"){
              Swal.fire({
                title: "Sucesso!",
                text: "Chamada salva!",
                icon: "success",
                confirmButtonColor: "#f75808",
              }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                  window.location.href = '/Aplicativo/Chamada'; // Substitua pela URL de destino
                } else if (result.isDenied) {
                  window.location.href = '/Aplicativo/Chamada'; // Substitua pela URL de destino
                }
              });
            }
          }
        });
      }
    }





    
});


  $('#pesquisar').on('click', function() {
    var data_filtro = $("#data_filtro").val();
    var turma = $('#turma').val();

    window.location.href = '/Aplicativo/ListaChamada/'+turma+'/'+data_filtro;

    /* $.ajax({
      type: "POST",
      url: '/Aplicativo/ChamadaInserir/'+turma,
      data: {
        data_filtro: data_filtro,
        turma: turma
      },
      dataType: 'json',
      success: function (data) {
        if(data.msg == "success"){
          $('#modal-success .modal-body p').text('Chamada salva!');
          $('#modal-success').modal('show');
        }
      }
    }); */
  })





  // Evento de clique no botão Salvar
  $('#gravar_acompanhamento').on('click', function() {

    var data_acompanhamento = $("#data_acompanhamento").val();
    var turma = $("#turma").val();
    var semestre = $("#semestre").val();
    var aluno = $("#aluno").val();
    var eu_outros = $("#eu_outros").val();
    var corpo_gestos = $("#corpo_gestos").val();
    var tracos_sons = $("#tracos_sons").val();
    var escuta_fala = $("#escuta_fala").val();
    var espaco_tempos = $("#espaco_tempos").val();
    var estrategias = $("#estrategias").val();
    var recomendacoes = $("#recomendacoes").val();
    var acompanhamento_id = $("#acompanhamento_id").val();



    $.ajax({
      type: "POST",
      url: '/Aplicativo/AcompanhamentoInserir',
      data: {
        data_acompanhamento: data_acompanhamento,
        turma: turma,
        semestre: semestre,
        aluno: aluno,
        eu_outros: eu_outros,
        corpo_gestos: corpo_gestos,
        tracos_sons: tracos_sons,
        escuta_fala: escuta_fala,
        espaco_tempos: espaco_tempos,
        estrategias: estrategias,
        recomendacoes: recomendacoes, 
        acompanhamento_id: acompanhamento_id
      },
      dataType: 'json',
      success: function (data) {
        if(data.msg == "success"){
          Swal.fire({
            title: "Sucesso!",
            text: "Acompanhamento salvo!",
            icon: "success",
            confirmButtonColor: "#f75808",
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
              window.location.href = '/Aplicativo/AcompanhamentoNovo/'+turma; // Substitua pela URL de destino
            } else if (result.isDenied) {
              window.location.href = '/Aplicativo/AcompanhamentoNovo/'+turma; // Substitua pela URL de destino
            }
          });
        }
      }
    });    
  });



  $('#gravar_registro').on('click', function() {

    var data_acompanhamento = $("#data_acompanhamento").val();
    var turma = $("#turma").val();
    var semestre = $("#semestre").val();
    var aluno = $("#aluno").val();
    var descricao = $("#descricao").val();



    $.ajax({
      type: "POST",
      url: '/Aplicativo/RegistroInserir',
      data: {
        data_acompanhamento: data_acompanhamento,
        turma: turma,
        semestre: semestre,
        aluno: aluno,
        descricao: descricao
      },
      dataType: 'json',
      success: function (data) {
        if(data.msg == "success"){
          Swal.fire({
            title: "Sucesso!",
            text: "Diagnóstico da sala salvo!",
            icon: "success",
            confirmButtonColor: "#f75808",
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
              window.location.href = '/Aplicativo/RegistroNovo/'+turma; // Substitua pela URL de destino
            } else if (result.isDenied) {
              window.location.href = '/Aplicativo/RegistroNovo/'+turma; // Substitua pela URL de destino
            }
          });
        }
      }
    });    
  });

$('#modal-success').on('hidden.bs.modal', function () {
  var tipo = $('#tipo').val();
  var turma = $('#turma').val();
  if(tipo == "O"){
    window.location.href = '/Aplicativo/ListaOcorrencia/'+turma; // Substitua pela URL de destino
  }else if(tipo == "A"){
    window.location.href = '/Aplicativo/AcompanhamentoNovo/'+turma; // Substitua pela URL de destino
  }else{
    window.location.href = '/Aplicativo/Chamada'; // Substitua pela URL de destino
  }
});


// Pegar o valor do input hidden
var alunosJustificativas = $('#alunos_justificativas').val();

// Converter o valor para um objeto JSON
var alunosJustificativasArray = JSON.parse(alunosJustificativas);

// Percorrer o array e exibir informações
alunosJustificativasArray.forEach(function(aluno) {
    // Faça o que precisar com os dados
    $('#checkbox_1-' + aluno.id_aluno).closest('.form-group').data('justificativa', aluno.justificativa);
});


// Lógica para capturar a justificativa ao abrir a modal
// Lógica para capturar a justificativa ao abrir a modal
$('img[data-toggle="modal"]').on('click', function() {
  var alunoId = $(this).closest('.form-group').find('input[type="checkbox"]').attr('id').split('-')[1];

  // Recupera a justificativa já existente para o aluno, se houver
  var justificativaExistente = $('#checkbox_1-' + alunoId).closest('.form-group').data('justificativa') || "";

  // Carrega a justificativa existente na modal
  $('#modal-center textarea').val(justificativaExistente);

  $('#modal-center .btn.btn-primary.float-right').off('click').on('click', function() {
      var justificativa = $('#modal-center textarea').val();

      // Adiciona ou atualiza a justificativa para o aluno
      $('#checkbox_1-' + alunoId).closest('.form-group').data('justificativa', justificativa);

      // Fecha a modal
      $('#modal-center').modal('hide');
  });
});



}

window.onload = function () {
    var base_url = window.location.origin + "/";
  
    /* Verifica permissoes dos usuarios*/
    $('#permissoes_usuarios').change(function () {
      var usuario = $('#permissoes_usuarios').val();
  
      $('.checkboxes').each(function () {
        $(this).attr("checked", false);
        $(this).parents('span').removeClass("checked");
      });
  
      $.ajax({
        type: "POST",
        url: base_url + 'Configuracoes/Permissoes/permissoesConsultarUsuario',
        data: {
          'usuario': usuario,
          csrf_token: $('#crsf_token').val()
        },
        dataType: 'json',
        success: function (data) {
          if (data.length > 0) {
            var x = 0;
            for (x = 0; x < data.length; x++) {
              $('.checkboxes').each(function (index) {
                if ($(this).val() == data[x].FK_ID_METODO) {
                  $(this).attr("checked", true);
                  $(this).parents('span').addClass("checked");
                }
              });
            }
          }
        }
      });
    });
  
    $('#permissoes_perfil').change(function () {
      var perfil = $(this).val();
  
      $('.checkboxes').each(function () {
        $(this).attr("checked", false);
        $(this).parents('span').removeClass("checked");
      });
  
      $.ajax({
        type: "POST",
        url: base_url + 'Configuracoes/Perfil/perfilConsultarPermissoes',
        data: {
          'id': perfil,
          csrf_token: $('#crsf_token').val()
        },
        dataType: 'json',
        success: function (data) {
          if (data.length > 0) {
            var x = 0;
            for (x = 0; x < data.length; x++) {
              $('.checkboxes').each(function (index) {
                if ($(this).val() == data[x].FK_ID_METODO) {
                  $(this).attr("checked", true);
                  $(this).parents('span').addClass("checked");
                }
              });
            }
          }
        }
      });
    });
  
    /** Verifica antes de salvar as permissoes */
    $('#gravar_permissoes').click(function () {
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
                $('#frmPermissoes').submit();
            }else{
                swal("Cancelado", "Operação cancelada! Dados não gravados", "error");
            }
        });
    });
  
    /** MARCAR TODAS AS CHECKBOX -> PERMISSOES */
    $("#permissoes_chk_all").click(function () {
      if ($('#permissoes_chk_all').prop('checked')) {
        $('.checkboxes').each(function (index) {
          $(this).attr("checked", true);
          $(this).parents('span').addClass("checked");
        });
      } else {
        $('.checkboxes').each(function () {
          $(this).attr("checked", false);
          $(this).parents('span').removeClass("checked");
        });
      }
  
    });
  };
  
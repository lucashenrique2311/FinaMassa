"use strict";
var KTDatatablesBasicScrollable = function () {

    var initTable1 = function () {
        var base_url = window.location.origin + "/";
        $('.numero_cartao').inputmask('9999 9999 9999 9999');
        $('.vencimento_cartao').inputmask('99/9999');

        $("#adicionarElemento").click(function () {
            var novoElemento = `
                <div class="col-md-2 elemento">
                    <h5>Telefone</h5>
                    <div class="controls">
                        <span style="font-size: 12px; font-weight: bold;">Considerar dígito 9?</span>
                        <input type="checkbox" class="digito_telefone_dinamico"> 
                        <input type="text" name="telefone_cliente" id="telefone_cliente" class="form-control telefone telefone_dinamico" value="" >
                        <button style="margin-top: 5px; background-color: #db0000; color: #FFFFFF;" type="button" class="btn remover">Remover <i style="color: #FFFFFF;" class="fa fa-trash"></i></button>
                    </div>
                </div>
            `;
            $(".secao_telefone").append(novoElemento);

            $('.telefone').inputmask('(99)99999-9999');
        });

        $(document).on("click", ".remover", function () {
            $(this).closest(".elemento").remove();
        });

        $("#repor_localizacao").select2();


        $("#imprimir_separacao").click(function () {
            var data_inicial_tabela = $("#data_inicial_tabela").val();
            var data_final_tabela = $("#data_final_tabela").val();
            var repor_localizacao = $("#repor_localizacao").val();
            var url = "/Integracao/imprimirSeparacao?data_inicial_tabela=" + data_inicial_tabela +
                "&data_final_tabela=" + data_final_tabela +
                "&repor_localizacao=" + repor_localizacao;

            window.open(url, '_blank'); // Abre a URL em uma nova guia
        });

        $("#imprimir_mercadorias").click(function () {
            window.location.href = "/Integracao/imprimirMercadorias";
        })

        $("#agrupar_pedido").click(function () {
            var data_inicial_tabela = $("#data_inicial_tabela").val()
            var data_final_tabela = $("#data_final_tabela").val()
            var numero_pedido = $("#numero_pedido").val()
            window.location.href = "/Integracao/blingSeparação?data_inicial_tabela=" + data_inicial_tabela + "&data_final_tabela=" + data_final_tabela + "&numero_pedido=" + numero_pedido + "&agrupar_pedido=S";
        })

        $("#deagrupar_pedido").click(function () {
            var data_inicial_tabela = $("#data_inicial_tabela").val()
            var data_final_tabela = $("#data_final_tabela").val()
            var numero_pedido = $("#numero_pedido").val()
            window.location.href = "/Integracao/blingSeparação?data_inicial_tabela=" + data_inicial_tabela + "&data_final_tabela=" + data_final_tabela + "&numero_pedido=" + numero_pedido + "&agrupar_pedido=N";
        })

        $("#agrupar_pedido_compra").click(function () {
            var data_inicial_tabela = $("#data_inicial_tabela").val()
            var data_final_tabela = $("#data_final_tabela").val()
            var estoque_seguranca = $("#estoque_seguranca").val()
            var fornecedores = $("#fornecedores").val()
            window.location.href = "/Integracao/integracaoBling?data_inicial_tabela=" + data_inicial_tabela + "&data_final_tabela=" + data_final_tabela + "&fornecedores=" + fornecedores + "&estoque_seguranca=" + estoque_seguranca + "&agrupar_pedido=S";
        })

        $("#limpar_pedidos").click(function () {
            window.location.href = "/Integracao/limparPedidos";
        })


        $('#kt_daterangepicker_1, #kt_daterangepicker_1_modal').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
        });

        $(".repor-localizacao").on('change', function () {
            // Obtenha a linha correspondente ao checkbox
            var row = $(this).closest('tr');

            // Extraia o número do pedido (ou outro identificador necessário)
            var numeroPedido = row.find('.numero_pedido').text();

            // Verifique se o checkbox está marcado ou não
            var reporLocalizacao = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/atualizaTmpPedido',
                data: {
                    'numeroPedido': numeroPedido,
                    'reporLocalizacao': reporLocalizacao
                },
                dataType: 'json',
                success: function (data) {
                    if (data != "") {

                    }
                }
            });


        });



        $("#marketplaces").on('change', function () {
            var marketplace = $(this).val()
            var tipo_anuncio = ""

            if (marketplace != "ml") {
                $.ajax({
                    type: "POST",
                    url: base_url + 'Integracao/getTaxaMkt',
                    data: {
                        'marketplace': marketplace,
                        'tipo_anuncio': tipo_anuncio
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data != "") {
                            $("#imposto").val(data.dados.PERCENTUAL_IMPOSTO)
                            $("#taxa_mkt").val(data.dados.PERCENTUAL_TAXA_MKT)
                            $("#lucro").val(data.dados.PERCENTUAL_LUCRO)
                            $("#taxa_fixa_mkt").val(data.dados.TAXA_FIXA_MKT)
                            $("#outros_custos").val(data.dados.OUTROS_CUSTOS)
                            $("#outros_custos_percentual").val(data.dados.PERCENTUAL_OUTROS_CUSTOS)
                        }
                    }
                });
            }
        })

        $("#tipo_anuncio").on('change', function () {
            var marketplace = $("#marketplaces").val()
            var tipo_anuncio = $(this).val()

            if (marketplace == "ml") {
                $.ajax({
                    type: "POST",
                    url: base_url + 'Integracao/getTaxaMkt',
                    data: {
                        'marketplace': marketplace,
                        'tipo_anuncio': tipo_anuncio
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data != "") {
                            $("#imposto").val(data.dados.PERCENTUAL_IMPOSTO)
                            if (tipo_anuncio == "gold_pro") {
                                $("#taxa_mkt").val(data.dados.PERCENTUAL_TAXA_MKT)
                                $("#lucro").val(data.dados.PERCENTUAL_LUCRO)
                                $("#taxa_fixa_mkt").val(data.dados.TAXA_FIXA_MKT)
                                $("#outros_custos").val(data.dados.OUTROS_CUSTOS)
                                $("#outros_custos_percentual").val(data.dados.PERCENTUAL_OUTROS_CUSTOS)
                            } else {
                                $("#taxa_mkt").val(data.dados.PERCENTUAL_TAXA_MKT_CLASSICO)
                                $("#lucro").val(data.dados.PERCENTUAL_LUCRO_CLASSICO)
                                $("#taxa_fixa_mkt").val(data.dados.TAXA_FIXA_MKT_CLASSICO)
                                $("#outros_custos").val(data.dados.OUTROS_CUSTOS_CLASSICO)
                                $("#outros_custos_percentual").val(data.dados.PERCENTUAL_OUTROS_CUSTOS_CLASSICO)

                            }
                        }
                    }
                });
            }
        })


        $(".campo_valor_alteracao").blur(function () {
            var id = $(this).attr('id')
            var valor = $(this).val()
            var valor_custo = valor.replace(",", ".");
            console.log(valor_custo)

            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/atualizaCustoProdutoAnuncio',
                data: {
                    id_anuncio: id,
                    valor: valor_custo
                },
                dataType: 'json',
                success: function (data) {
                    /* if(data){
                        if(data.msg == "sucesso"){
                            Swal.fire({
                              title: 'Sucesso',
                              text: "Custos atualizado com sucesso",
                              icon: 'success',
                              showCancelButton: false,
                              confirmButtonColor: '#3085d6',
                              confirmButtonText: 'OK!'
                            }).then((result) => {
                              if (result.isConfirmed) {
                                Swal.close()
                              }
                            })
                        }else{
                            Swal.fire({
                            title: 'Erro',
                            text: "Houve algum erro ao realizar a ação",
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#bf0f0f',
                            confirmButtonText: 'OK!'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.close()
                            }
                            })
                        }
                    }  */
                }
            });





        })

        $(".campo_valor_alteracao_novo").blur(function () {
            var id = $(this).attr('id')
            var valor = $(this).val()
            var valor_custo = valor.replace(",", ".");
            console.log(valor_custo)

            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/atualizaPrecoProdutoAnuncio',
                data: {
                    id_anuncio: id,
                    valor: valor_custo
                },
                dataType: 'json',
                success: function (data) {
                    /* if(data){
                        if(data.msg == "sucesso"){
                            Swal.fire({
                              title: 'Sucesso',
                              text: "Custos atualizado com sucesso",
                              icon: 'success',
                              showCancelButton: false,
                              confirmButtonColor: '#3085d6',
                              confirmButtonText: 'OK!'
                            }).then((result) => {
                              if (result.isConfirmed) {
                                Swal.close()
                              }
                            })
                        }else{
                            Swal.fire({
                            title: 'Erro',
                            text: "Houve algum erro ao realizar a ação",
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#bf0f0f',
                            confirmButtonText: 'OK!'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.close()
                            }
                            })
                        }
                    }   */
                }
            });





        })



        $('#dados_bling tbody').on('click', '.btn-atualiza-pedido', function () {
            var id = $(this).attr("id");
            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/atualizaPedido',
                data: {
                    id_pedido: id,
                },
                dataType: 'json',
                success: function (data) {
                    if (data) {
                        location.reload();
                    }
                }
            });
        })


        $(document).keydown(function (e) {
            if (e.keyCode == 86 && e.ctrlKey) {
                var i = 0;
                $('#dados_bling tr.linhas').each(function () {
                    i += 1;
                });

                if (i == 1) {
                    $(".btn-atualiza-pedido").trigger('click')

                }
            }
        });

        $(".ul-conta_ml").hide()
        $(".btn-atualiza-pedido").hide();
        $(document).keydown(function (e) {
            if (e.keyCode == 77 && e.ctrlKey) {
                $(".btn-atualiza-pedido").show();
            }
        });
        $(document).keydown(function (e) {
            if (e.keyCode == 66 && e.ctrlKey) {
                $(".btn-atualiza-pedido").hide();
            }
        });


        var dados_bling = $('#dados_bling').DataTable({
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
                    "copy": "Copiar",
                    "print": "Imprimir",
                    "colvis": "Colunas"
                },
            },
            "pageLength": 10000,
            scrollX: true,
            scrollY: 500,
            "info": true,
            select: "multi",
            dom: 'Bfrtip',
            "columnDefs": [
                { "visible": false, "targets": [0, 5] }
            ],
            buttons: [
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    className: 'botao_export',
                    pageSize: 'LEGAL',
                    footer: true,
                    customize: function (doc) {
                        // Substitui o checkbox com "Sim" ou "Não" no PDF
                        var rows = $('#dados_bling').find('tbody tr');
                        for (var i = 0; i < rows.length; i++) {
                            var checkbox = $(rows[i]).find('.repor-localizacao');
                            if (checkbox.length) {
                                var isChecked = checkbox.prop('checked') ? 'Sim' : 'Não';
                                doc.content[1].table.body[i + 1][11].text = isChecked;
                            }
                        }
                    }
                },
                { extend: 'csv', className: 'botao_export', footer: true },
                { extend: 'excel', className: 'botao_export', footer: true },
                { extend: 'colvis', className: 'botao_export', },
                {
                    text: 'Desmarcar todos',
                    className: 'botao_export',
                    action: function () {
                        dados_bling.rows().deselect();
                    }
                },
            ],
        });

        /*         $('div.dataTables_filter input', dados_bling.table().container()).focus();
        
                $('div.dataTables_filter input').blur(function(){
                $('div.dataTables_filter input', dados_bling.table().container()).val("");
                $('div.dataTables_filter input', dados_bling.table().container()).focus();
                }) */



        /* $('div.dataTables_filter input').bind('change', function() {
            $("#input_datatable").val($(this).val())

        
            setTimeout(() => {    
                if($("#input_datatable").val() != "" && $("#input_datatable").val() != undefined){
                    console.log("ok")
                    setInterval(() => {
                        location.reload();
                    }, 25000);
                }
            }, 1000);

        });

        $('.dataTables_filter input[type="search"]').css(
        {'width':'350px','display':'inline-block','height': '50px', 'font-size':'20px', 'border-color':'#DDDDDD'}
        ); */

        var dados_curva_abc = $('#dados_curva_abc').DataTable({
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
                    "copy": "Copiar",
                    "print": "Imprimir",
                    "colvis": "Colunas"
                },
            },
            "pageLength": 10000,
            scrollX: true,
            scrollY: 800,
            order: [[4, 'desc']],
            "info": true,
            select: "multi",
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'botao_export',
                    footer: true
                },
                { extend: 'csv', className: 'botao_export', footer: true },
                { extend: 'excel', className: 'botao_export', footer: true },
                { extend: 'colvis', className: 'botao_export', },
                {
                    text: 'Desmarcar todos',
                    className: 'botao_export',
                    action: function () {
                        dados_bling.rows().deselect();
                    }
                },
            ],
        });

        // Definindo uma função de ordenação customizada para inputs numéricos
        $.fn.dataTable.ext.order['dom-text-numeric'] = function (settings, colIndex) {
            return this.api().column(colIndex, { order: 'index' }).nodes().map(function (td, i) {
                return $('input', td).val() * 1; // Convertendo para número
            });
        };

        var dados_bling_pedido = $('#dados_bling_pedido').DataTable({
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
                    "copy": "Copiar",
                    "print": "Imprimir",
                    "colvis": "Colunas"
                },
            },
            "pageLength": 10000,
            scrollX: true,
            fixedHeader: true,
            "info": true,
            select: "multi",
            "columnDefs": [
                {
                    "targets": 14, // Índice da coluna "QTD PEDIDO"
                    "orderDataType": "dom-text-numeric", // Usa a função de ordenação customizada
                    "type": "numeric"
                }
            ],
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    className: 'botao_export',
                    pageSize: 'LEGAL',
                    footer: true,
                    customize: function (doc) {
                        var body = doc.content[1].table.body;
                        $('#dados_bling_pedido tbody tr').each(function (index) {
                            var inputVal = $(this).find('input.input_qtd_pedido').val();
                            var colIndex = 0;
                            $(this).find('td').each(function (i) {
                                if ($(this).find('input.input_qtd_pedido').length) {
                                    colIndex = i + 1;
                                }
                            });
                            if (body[index + 1] && body[index + 1][colIndex]) {
                                body[index + 1][colIndex].text = inputVal;
                            }
                        });
                    }
                },
                {
                    extend: 'excelHtml5',
                    className: 'botao_export',
                    footer: true
                },
                {
                    extend: 'csv',
                    className: 'botao_export',
                    footer: true
                },
                {
                    extend: 'colvis',
                    className: 'botao_export',
                },
                {
                    text: 'Desmarcar todos',
                    className: 'botao_export',
                    action: function () {
                        dados_bling_pedido.rows().deselect();
                    }
                },
            ],
        });

        var analise_produto = $('#analise_produto').DataTable({
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
                    "copy": "Copiar",
                    "print": "Imprimir",
                    "colvis": "Colunas"
                },
            },
            "pageLength": 10000,
            scrollX: true,
            scrollY: 500,
            order: [[0, 'asc']],
            "info": true,
            select: "multi",
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'botao_export',
                    footer: true
                },
                { extend: 'csv', className: 'botao_export', footer: true },
                { extend: 'excel', className: 'botao_export', footer: true },
                { extend: 'colvis', className: 'botao_export', },
                {
                    text: 'Desmarcar todos',
                    className: 'botao_export',
                    action: function () {
                        dados_bling.rows().deselect();
                    }
                },
            ],
        });

        var alteracao_produto = $('#alteracao_produto').DataTable({
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
                    "copy": "Copiar",
                    "print": "Imprimir",
                    "colvis": "Colunas"
                },
            },
            "pageLength": 10000,
            scrollY: 500,
            scrollX: true // Habilita o scroll horizontal
            "info": true,
            select: "multi",
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    className: 'botao_export',
                    footer: true
                },
                { extend: 'csv', className: 'botao_export', footer: true },
                { extend: 'excel', className: 'botao_export', footer: true },
                { extend: 'colvis', className: 'botao_export', },
            ],
        });

        $('.percentual').maskMoney({

            suffix: ' %',
            affixesStay: false
        });

        $('.telefone').inputmask('(99)99999-9999');

        $('.valor').maskMoney({

            prefix: 'R$ ',

            allowNegative: true,

            thousands: '',

            decimal: ',',

            affixesStay: false

        });

        $('#alterar_preco').click(function () {
            Swal.fire({
                title: 'Deseja alterar o preço dos produtos selecionados?',
                text: "Certifique-se de ter verificado todos os dados antes de efetuar a ação!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Pode salvar',
                cancelButtonText: "Não, cancele!",
            }).then((result) => {
                if (result.isConfirmed) {
                    var id_anuncio = [];
                    var representante = [];
                    var x = 0;
                    var imposto = $("#imposto").val().replace(",", ".");
                    var taxa_mkt = $("#taxa_mkt").val().replace(",", ".");
                    var taxa_fixa_mkt = $("#taxa_fixa_mkt").val().replace(",", ".");
                    var lucro = $("#lucro").val().replace(",", ".");
                    var frete_valor = $("#frete_valor").val().replace(",", ".");
                    var gnre = $("#gnre").val().replace(",", ".");
                    var embalagem = $("#embalagem").val().replace(",", ".");
                    var outros_custos = $("#outros_custos").val().replace(",", ".");
                    var outros_custos_percentual = $("#outros_custos_percentual").val().replace(",", ".");

                    $('#alteracao_produto tr.selected').each(function () {

                        id_anuncio[x] = $(this).find('.id_anuncio').html();
                        var preco_antigo = $(this).find('.preco_antigo').html();
                        preco_antigo = preco_antigo.split(" ")
                        preco_antigo = preco_antigo[1].replace(",", ".")
                        preco_antigo = parseFloat(preco_antigo)

                        var input_custo = $(this).find('.input_custo').val().replace(",", ".");




                        if (input_custo != 0) { input_custo = parseFloat(input_custo) } else { input_custo = 0; }
                        if (frete_valor != 0) { frete_valor = parseFloat(frete_valor) } else { frete_valor = 0; }
                        if (gnre != 0) { gnre = parseFloat(gnre) } else { gnre = 0; }
                        if (embalagem != 0) { embalagem = parseFloat(embalagem) } else { embalagem = 0; }
                        if (outros_custos != 0) { outros_custos = parseFloat(outros_custos) } else { outros_custos = 0; }
                        if (taxa_fixa_mkt != 0) { taxa_fixa_mkt = parseFloat(taxa_fixa_mkt) } else { taxa_fixa_mkt = 0; }

                        if (imposto != 0) { imposto = parseFloat(imposto) } else { imposto = 0; }
                        if (taxa_mkt != 0) { taxa_mkt = parseFloat(taxa_mkt) } else { taxa_mkt = 0; }
                        if (lucro != 0) { lucro = parseFloat(lucro) } else { lucro = 0; }
                        if (outros_custos_percentual != 0) { outros_custos_percentual = parseFloat(outros_custos_percentual) } else { outros_custos_percentual = 0; }

                        var somatorio_valor = input_custo + frete_valor + gnre + embalagem + outros_custos + taxa_fixa_mkt;
                        var somatorio_percentual = imposto + taxa_mkt + lucro + outros_custos_percentual;
                        var novo_valor = (somatorio_valor / (100 - somatorio_percentual)) * 100;
                        novo_valor = novo_valor.toFixed(2)

                        console.log(preco_antigo)
                        console.log(novo_valor)
                        if (novo_valor < preco_antigo) {
                            console.log("valor menor")
                        }
                        $(this).find('.situacao').empty('');
                        $(this).find('.situacao').append('<span class="badge badge-success">Atualizado</span>');

                        $(this).find('.input_preco').val(novo_valor);

                        x++;
                    });
                    if (id_anuncio == null || id_anuncio == "") {
                        Swal.fire({
                            title: 'Erro',
                            text: "Nenhum anuncio selecionado",
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#bf0f0f',
                            confirmButtonText: 'OK!'
                        })
                    } else {
                        var imposto = $("#imposto").val();
                        var taxa_mkt = $("#taxa_mkt").val();
                        var taxa_fixa_mkt = $("#taxa_fixa_mkt").val();
                        var lucro = $("#lucro").val();
                        var frete_valor = $("#frete_valor").val();
                        var gnre = $("#gnre").val();
                        var embalagem = $("#embalagem").val();
                        var outros_custos = $("#outros_custos").val();
                        var outros_custos_percentual = $("#outros_custos_percentual").val();

                        $.ajax({
                            type: "POST",
                            url: base_url + 'Integracao/atualizaPrecoProduto',
                            data: {
                                'id_anuncio': id_anuncio,
                                'imposto': imposto,
                                'taxa_mkt': taxa_mkt,
                                'taxa_fixa_mkt': taxa_fixa_mkt,
                                'lucro': lucro,
                                'frete_valor': frete_valor,
                                'gnre': gnre,
                                'embalagem': embalagem,
                                'outros_custos': outros_custos
                            },
                            dataType: 'json',
                            success: function (data) {
                                /* if(data.msg == "sucesso"){
                                  Swal.fire({
                                    title: 'Sucesso',
                                    text: "Preços atualizado com sucesso",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK!'
                                  }).then((result) => {
                                    if (result.isConfirmed) {
                                      location.reload();
                                    }
                                  })
                                }else{
                                    Swal.fire({
                                      title: 'Erro',
                                      text: "Houve algum erro ao realizar a ação",
                                      icon: 'error',
                                      showCancelButton: false,
                                      confirmButtonColor: '#bf0f0f',
                                      confirmButtonText: 'OK!'
                                    }).then((result) => {
                                      if (result.isConfirmed) {
                                        location.reload();
                                      }
                                    })
                                } */
                            }
                        });
                    }

                }
            })


        });

        $('#sincronizar_bling_produto').click(function () {
            Swal.fire({
                title: 'Deseja sincronizar os dados com o bling?',
                text: "Certifique-se de ter verificado todos os dados antes de efetuar a ação!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Pode salvar',
                cancelButtonText: "Não, cancele!",
            }).then((result) => {
                if (result.isConfirmed) {
                    var id_anuncio = [];
                    var representante = [];
                    var x = 0;
                    $('#alteracao_produto tr.selected').each(function () {
                        id_anuncio[x] = $(this).find('.id_anuncio').html();
                        x++;
                    });
                    if (id_anuncio == null || id_anuncio == "") {
                        Swal.fire({
                            title: 'Erro',
                            text: "Nenhum anuncio selecionado",
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#bf0f0f',
                            confirmButtonText: 'OK!'
                        })
                    } else {
                        Swal.fire({
                            title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                            html: '<strong> <h2> Enviando informações. </br> Isso pode levar alguns minutos, avisaremos quando finalizar! <br><br> Enquanto isso, que tal tomar um cafézinho <i style="color: #783838;" class="icon-xl fas fa-coffee"></i></h2  ></strong>',
                            showCloseButton: false,
                            showCancelButton: false,
                            focusConfirm: false,
                        })

                        $.ajax({
                            type: "POST",
                            url: base_url + 'Integracao/sicnronizaBling',
                            data: {
                                'id_anuncio': id_anuncio
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data.msg == "sucesso") {
                                    Swal.fire({
                                        title: 'Sucesso',
                                        text: "Preços atualizado com sucesso",
                                        icon: 'success',
                                        showCancelButton: false,
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'OK!'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            location.reload();
                                        }
                                    })
                                } else {
                                    Swal.fire({
                                        title: 'Erro',
                                        text: "Houve algum erro ao realizar a ação",
                                        icon: 'error',
                                        showCancelButton: false,
                                        confirmButtonColor: '#bf0f0f',
                                        confirmButtonText: 'OK!'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            location.reload();
                                        }
                                    })
                                }
                            }
                        });
                    }

                }
            })


        });

        $('#limpa_tabela').click(function () {
            Swal.fire({
                title: 'Deseja limpar os registros da tabela?',
                text: "Certifique-se de ter verificado todos os dados antes de efetuar a ação!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Pode limpar',
                cancelButtonText: "Não, cancele!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'Integracao/limpaTabelaAnuncio',
                        data: {},
                        dataType: 'json',
                        success: function (data) {
                            if (data.msg == "sucesso") {
                                Swal.fire({
                                    title: 'Sucesso',
                                    text: "Registros removidos com sucesso",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            } else {
                                Swal.fire({
                                    title: 'Erro',
                                    text: "Houve algum erro ao realizar a ação",
                                    icon: 'error',
                                    showCancelButton: false,
                                    confirmButtonColor: '#bf0f0f',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            }
                        }
                    });

                }
            })


        });

        $('#limpa_tabela_full').click(function () {
            Swal.fire({
                title: 'Deseja limpar os registros da tabela?',
                text: "Certifique-se de ter verificado todos os dados antes de efetuar a ação!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Pode limpar',
                cancelButtonText: "Não, cancele!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'Integracao/limpaTabelafull',
                        data: {},
                        dataType: 'json',
                        success: function (data) {
                            if (data.msg == "sucesso") {
                                Swal.fire({
                                    title: 'Sucesso',
                                    text: "Registros removidos com sucesso",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            } else {
                                Swal.fire({
                                    title: 'Erro',
                                    text: "Houve algum erro ao realizar a ação",
                                    icon: 'error',
                                    showCancelButton: false,
                                    confirmButtonColor: '#bf0f0f',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            }
                        }
                    });

                }
            })


        });

        dados_bling_pedido.on('page.dt', function () {
            $('html, body').animate({
                scrollTop: $(".dataTables_wrapper").offset().top
            }, 'slow');
        });

        $('#dados_bling_pedido tbody').on('click', 'tr', function () {
            $(this).toggleClass('selected');
            $(this).focus();
        });

        // $(".input_qtd_pedido").click(function(){
        //     console.log($(this).parent())
        //     $(this).parent().parent().toggleClass('selected');        
        // })

        $(".input_qtd_pedido").blur(function () {
            //console.log($(this).parent())
            //$(this).parent().parent().toggleClass('selected');
            var id = $(this).attr('id')
            var valor = $(this).val()
            $("#span_" + id).empty("")
            $("#span_" + id).append(valor)

            var valor_preco = $("#input_preco_original_" + id).val()
            valor_preco = valor_preco.split(" ")
            valor_preco = valor_preco[1] * valor;

            $("#span_preco_" + id).empty("")
            $("#span_preco_" + id).append("R$ " + valor_preco.toFixed(2))






            dados_bling_pedido.rows().invalidate().draw();

            atualizaTotal()



        })

        function atualizaTotal() {
            var total = 0;
            $('#dados_bling_pedido tr.linhas').each(function () {
                var preco_html = $(this).find('.span_preco').html();
                preco_html = preco_html.split(" ")
                preco_html = parseFloat(preco_html[1])
                total += preco_html;

            });

            total = total.toFixed(2)
            total = total.toString()
            total = total.replace(".", ",")

            $(dados_bling_pedido.column(8).footer()).html("R$ " + total);

            dados_bling_pedido.rows().invalidate().draw();
        }

        $("#criar_pedido").click(function () {
            var fornecedor = $("#fornecedores").val();

            if (fornecedor != "") {
                var dados_pedido = [];
                var x = 0;
                $('#dados_bling_pedido tr.linhas').each(function () {
                    if ($(this).find('.input_qtd_pedido').val() != "0" && $(this).find('.input_qtd_pedido').val() != "") {

                        dados_pedido[x] = {
                            "preco_custo": $(this).find('.input_preco_original').val(),
                            "qtd_pedido": $(this).find('.input_qtd_pedido').val(),
                            "sku_produto": $(this).find('.sku_produto').html(),
                            "descricao_completa_produto": $(this).find('.descricao_completa_produto').html(),
                            "id_produto_bling": $(this).find('.id_produto_bling').html()
                        }
                    }

                    x++;
                });

                $.ajax({
                    type: "POST",
                    url: base_url + 'Integracao/criaPedidoCompra',
                    data: {
                        dados_pedido: dados_pedido,
                        fornecedor: fornecedor
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data === "sucesso") {
                            Swal.fire({
                                title: 'Sucesso',
                                text: "Pedido de compra criado com sucesso!",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            })
                        }
                    }
                });
            } else {
                Swal.fire({
                    title: 'Erro',
                    text: "Ocorreu algum erro ao realizar o pedido de compra! forncedor não foi preenchido",
                    icon: 'error',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                })
            }

        })


        $('#gravar_cartao').click(function () {
            Swal.fire({
                title: 'Deseja salvar?',
                text: "Certifique-se de ter verificado todos os dados antes de efetuar a ação!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Pode salvar',
                cancelButtonText: "Não, cancele!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#frmCartao_cadastro').submit();
                }
            })


        });

        $('#envia_form').click(function () {

            Swal.fire({
                title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                html: '<strong> <h2> Filtrando dados. </br> Isso pode levar alguns segundos!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
            })

            $('#frm_pesquisa').submit();

            setTimeout(() => {
                Swal.fire({
                    title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguardar só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                })

            }, 50000);

            setTimeout(() => {
                Swal.fire({
                    title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguardar só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                })

            }, 90000);




        });


        $(".secao_dados_ml").hide();

        $("#multi_conta_ml").on('change', function () {

            $.ajax({
                type: "POST",
                url: base_url + '/Integracao/getDadosContaMl',
                data: {
                    'id_mercado_livre': $(this).val(),
                },
                dataType: 'json',
                success: function (data) {
                    console.log(data.dados_telefones)
                    if (data.dados[0].CONTA_UTILIZADA == "S") {
                        if (data.dados[0].NOME_CONTA != "" && data.dados[0].NOME_CONTA != null) {
                            $(".titulo_conta").html('<h3 class="titulo_conta" >Olá ' + data.dados[0].NOME_CONTA + '</h3>')
                        } else {
                            $(".titulo_conta").html('<h3 class="titulo_conta" >Olá</h3>')
                        }
                        $(".div_conta_existe").show()
                        $(".div_conta_nao_existe").hide()
                        $("#id_conta_ml").val(data.dados[0].ID_MERCADO_LIVRE)
                        $("#percentual_imposto").val(data.dados[0].PERCENTUAL_IMPOSTO)
                        $("#reputacao_ml").val(data.dados[0].NIVEL_REPUTACAO)
                        $("#regiao_ml").val(data.dados[0].REGIAO_ENVIO)
                        $("#taxa_mkt").val(data.dados[0].PERCENTUAL_TAXA_MKT)
                        $("#lucro").val(data.dados[0].PERCENTUAL_LUCRO)
                        $("#taxa_fixa_mkt").val(data.dados[0].TAXA_FIXA_MKT)
                        $("#outros_custos").val(data.dados[0].OUTROS_CUSTOS)
                        $("#outros_custos_percentual").val(data.dados[0].PERCENTUAL_OUTROS_CUSTOS)
                        $("#taxa_mkt_classico").val(data.dados[0].PERCENTUAL_TAXA_MKT_CLASSICO)
                        $("#lucro_classico").val(data.dados[0].PERCENTUAL_LUCRO_CLASSICO)
                        $("#taxa_fixa_mkt_classico").val(data.dados[0].TAXA_FIXA_MKT_CLASSICO)
                        $("#outros_custos_classico").val(data.dados[0].OUTROS_CUSTOS_CLASSICO)
                        $("#outros_custos_percentual_classico").val(data.dados[0].PERCENTUAL_OUTROS_CUSTOS_CLASSICO)
                        $("#telefone_cliente").val(data.dados[0].TELEFONE_CLIENTE)
                        if (data.dados[0].NOTIFICACAO_PERGUNTA == 1) {
                            $('#notificacao_perguntas').prop('checked', true);
                        }
                        $(".secao_dados_ml").show();
                    } else {
                        $(".div_conta_existe").hide()
                        $(".div_conta_nao_existe").show()
                        $(".secao_dados_ml").show();
                    }

                    if (data.dados_telefones != "") {
                        // Função para adicionar os elementos
                        function adicionarElemento(telefone, considerar_digito) {
                            if (considerar_digito == "S") {
                                var checked = "checked"
                            } else {
                                var checked = ""
                            }
                            var novoElemento = `
                                <div class="col-md-2 elemento">
                                    <h5>Telefone</h5>
                                    <div class="controls">
                                        <span style="font-size: 12px; font-weight: bold;">Considerar dígito 9?</span>
                                        <input type="checkbox" class="digito_telefone_dinamico" ${checked} > 
                                        <input type="text" name="telefone_cliente" class="form-control telefone telefone_dinamico" value="${telefone}" >
                                        <button style="margin-top: 10px; background-color: #db0000; color: #FFFFFF;" type="button" class="btn remover">Remover <i style="color: #FFFFFF;" class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                            `;
                            $(".secao_telefone").append(novoElemento);
                        }

                        console.log(data.dados_telefones.length)
                        // Adiciona os telefones do 'retorno' usando a função adicionarElemento
                        for (var i = 0; i < data.dados_telefones.length; i++) {
                            adicionarElemento(data.dados_telefones[i].TELEFONE, data.dados_telefones[i].CONSIDERAR_DIGITO_9);
                        }

                        $('.telefone').inputmask('(99)99999-9999');

                    }
                }
            });
        })


        $('#gravar_config_ml').click(function () {
            Swal.fire({
                title: 'Deseja salvar?',
                text: "Certifique-se de ter verificado todos os dados antes de efetuar a ação!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Pode salvar',
                cancelButtonText: "Não, cancele!",
            }).then((result) => {
                if (result.isConfirmed) {
                    var id_conta_ml = $("#id_conta_ml").val()
                    var percentual_imposto = $("#percentual_imposto").val();
                    var reputacao = $("#reputacao_ml").val();
                    var regiao = $("#regiao_ml").val();
                    var taxa_mkt = $("#taxa_mkt").val();
                    var lucro = $("#lucro").val();
                    var taxa_fixa_mkt = $("#taxa_fixa_mkt").val();
                    var outros_custos = $("#outros_custos").val();
                    var outros_custos_percentual = $("#outros_custos_percentual").val();
                    var taxa_mkt_classico = $("#taxa_mkt_classico").val();
                    var lucro_classico = $("#lucro_classico").val();
                    var taxa_fixa_mkt_classico = $("#taxa_fixa_mkt_classico").val();
                    var outros_custos_classico = $("#outros_custos_classico").val();
                    var outros_custos_percentual_classico = $("#outros_custos_percentual_classico").val();
                    // Dentro do seu código de envio AJAX
                    var telefones = [];

                    console.log("id_conta_ml")
                    console.log(id_conta_ml)
                    // Percorre os campos de telefone dinâmicos e adiciona seus valores à array 'telefones'
                    $('.telefone_dinamico').each(function () {
                        // Agora, encontra o checkbox associado a este telefone
                        var checkbox = $(this).siblings('.digito_telefone_dinamico');
                        var isChecked = checkbox.is(':checked'); // Verifica se o checkbox está marcado

                        // Faça o que for necessário com a informação do checkbox aqui
                        if (isChecked) {
                            var digito = "S";
                        } else {
                            var digito = "N";
                        }

                        var valorTelefone = $(this).val();
                        telefones.push(valorTelefone + "&" + digito);

                    });


                    // Agora 'telefones' contém todos os valores dos campos de telefone dinâmicos
                    var telefone_cliente = telefones.join(',');

                    if ($('#notificacao_perguntas').is(':checked')) {
                        var notificacao_perguntas = 1;
                    } else {
                        var notificacao_perguntas = 0;
                    }

                    $.ajax({
                        type: "POST",
                        url: base_url + '/Integracao/atualizaConfigML',
                        data: {
                            'id_conta_ml': id_conta_ml,
                            'percentual_imposto': percentual_imposto,
                            'reputacao': reputacao,
                            'regiao': regiao,
                            'taxa_mkt': taxa_mkt,
                            'lucro': lucro,
                            'taxa_fixa_mkt': taxa_fixa_mkt,
                            'outros_custos': outros_custos,
                            'outros_custos_percentual': outros_custos_percentual,
                            'taxa_mkt_classico': taxa_mkt_classico,
                            'lucro_classico': lucro_classico,
                            'taxa_fixa_mkt_classico': taxa_fixa_mkt_classico,
                            'outros_custos_classico': outros_custos_classico,
                            'outros_custos_percentual_classico': outros_custos_percentual_classico,
                            'telefone_cliente': telefone_cliente,
                            'notificacao_perguntas': notificacao_perguntas
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.msg == "sucesso") {
                                Swal.fire({
                                    title: 'Sucesso',
                                    text: "Dados atualizados com sucesso",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            } else {
                                Swal.fire({
                                    title: 'Erro',
                                    text: "Houve algum erro ao realizar a ação",
                                    icon: 'error',
                                    showCancelButton: false,
                                    confirmButtonColor: '#bf0f0f',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            }

                        }
                    });
                }
            })
        });

        $('#gravar_config_shopee').click(function () {
            Swal.fire({
                title: 'Deseja salvar?',
                text: "Certifique-se de ter verificado todos os dados antes de efetuar a ação!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Pode salvar',
                cancelButtonText: "Não, cancele!",
            }).then((result) => {
                if (result.isConfirmed) {
                    var percentual_imposto = $("#percentual_imposto_shopee").val();
                    var taxa_mkt = $("#taxa_mkt_shopee").val();
                    var lucro = $("#lucro_shopee").val();
                    var taxa_fixa_mkt = $("#taxa_fixa_mkt_shopee").val();
                    var outros_custos = $("#outros_custos_shopee").val();
                    var outros_custos_percentual = $("#outros_custos_percentual_shopee").val();


                    $.ajax({
                        type: "POST",
                        url: base_url + '/Integracao/atualizaConfigShopee',
                        data: {
                            'percentual_imposto': percentual_imposto,
                            'taxa_mkt': taxa_mkt,
                            'lucro': lucro,
                            'taxa_fixa_mkt': taxa_fixa_mkt,
                            'outros_custos': outros_custos,
                            'outros_custos_percentual': outros_custos_percentual,
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.msg == "sucesso") {
                                Swal.fire({
                                    title: 'Sucesso',
                                    text: "Dados atualizados com sucesso",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            } else {
                                Swal.fire({
                                    title: 'Erro',
                                    text: "Houve algum erro ao realizar a ação",
                                    icon: 'error',
                                    showCancelButton: false,
                                    confirmButtonColor: '#bf0f0f',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            }

                        }
                    });
                }
            })
        });

        $('#gravar_config_b2w').click(function () {
            Swal.fire({
                title: 'Deseja salvar?',
                text: "Certifique-se de ter verificado todos os dados antes de efetuar a ação!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Pode salvar',
                cancelButtonText: "Não, cancele!",
            }).then((result) => {
                if (result.isConfirmed) {
                    var percentual_imposto = $("#percentual_imposto_b2w").val();
                    var taxa_mkt = $("#taxa_mkt_b2w").val();
                    var lucro = $("#lucro_b2w").val();
                    var taxa_fixa_mkt = $("#taxa_fixa_mkt_b2w").val();
                    var outros_custos = $("#outros_custos_b2w").val();
                    var outros_custos_percentual = $("#outros_custos_percentual_b2w").val();

                    console.log(percentual_imposto)
                    console.log(taxa_mkt)
                    console.log(lucro)
                    console.log(taxa_fixa_mkt)
                    console.log(outros_custos)
                    console.log(outros_custos_percentual)
                    $.ajax({
                        type: "POST",
                        url: base_url + '/Integracao/atualizaConfigB2W',
                        data: {
                            'percentual_imposto': percentual_imposto,
                            'taxa_mkt': taxa_mkt,
                            'lucro': lucro,
                            'taxa_fixa_mkt': taxa_fixa_mkt,
                            'outros_custos': outros_custos,
                            'outros_custos_percentual': outros_custos_percentual,
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.msg == "sucesso") {
                                Swal.fire({
                                    title: 'Sucesso',
                                    text: "Dados atualizados com sucesso",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            } else {
                                Swal.fire({
                                    title: 'Erro',
                                    text: "Houve algum erro ao realizar a ação",
                                    icon: 'error',
                                    showCancelButton: false,
                                    confirmButtonColor: '#bf0f0f',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            }

                        }
                    });
                }
            })
        });
        $('#gravar_config_olist').click(function () {
            Swal.fire({
                title: 'Deseja salvar?',
                text: "Certifique-se de ter verificado todos os dados antes de efetuar a ação!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Pode salvar',
                cancelButtonText: "Não, cancele!",
            }).then((result) => {
                if (result.isConfirmed) {
                    var percentual_imposto = $("#percentual_imposto_olist").val();
                    var taxa_mkt = $("#taxa_mkt_olist").val();
                    var lucro = $("#lucro_olist").val();
                    var taxa_fixa_mkt = $("#taxa_fixa_mkt_olist").val();
                    var outros_custos = $("#outros_custos_olist").val();
                    var outros_custos_percentual = $("#outros_custos_percentual_olist").val();
                    $.ajax({
                        type: "POST",
                        url: base_url + '/Integracao/atualizaConfigOlist',
                        data: {
                            'percentual_imposto': percentual_imposto,
                            'taxa_mkt': taxa_mkt,
                            'lucro': lucro,
                            'taxa_fixa_mkt': taxa_fixa_mkt,
                            'outros_custos': outros_custos,
                            'outros_custos_percentual': outros_custos_percentual,
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.msg == "sucesso") {
                                Swal.fire({
                                    title: 'Sucesso',
                                    text: "Dados atualizados com sucesso",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            } else {
                                Swal.fire({
                                    title: 'Erro',
                                    text: "Houve algum erro ao realizar a ação",
                                    icon: 'error',
                                    showCancelButton: false,
                                    confirmButtonColor: '#bf0f0f',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            }

                        }
                    });
                }
            })
        });

        $('#gravar_config_magalu').click(function () {
            Swal.fire({
                title: 'Deseja salvar?',
                text: "Certifique-se de ter verificado todos os dados antes de efetuar a ação!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Pode salvar',
                cancelButtonText: "Não, cancele!",
            }).then((result) => {
                if (result.isConfirmed) {
                    var percentual_imposto = $("#percentual_imposto_magalu").val();
                    var taxa_mkt = $("#taxa_mkt_magalu").val();
                    var lucro = $("#lucro_magalu").val();
                    var taxa_fixa_mkt = $("#taxa_fixa_mkt_magalu").val();
                    var outros_custos = $("#outros_custos_magalu").val();
                    var outros_custos_percentual = $("#outros_custos_percentual_magalu").val();
                    $.ajax({
                        type: "POST",
                        url: base_url + '/Integracao/atualizaConfigMagalu',
                        data: {
                            'percentual_imposto': percentual_imposto,
                            'taxa_mkt': taxa_mkt,
                            'lucro': lucro,
                            'taxa_fixa_mkt': taxa_fixa_mkt,
                            'outros_custos': outros_custos,
                            'outros_custos_percentual': outros_custos_percentual,
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.msg == "sucesso") {
                                Swal.fire({
                                    title: 'Sucesso',
                                    text: "Dados atualizados com sucesso",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            } else {
                                Swal.fire({
                                    title: 'Erro',
                                    text: "Houve algum erro ao realizar a ação",
                                    icon: 'error',
                                    showCancelButton: false,
                                    confirmButtonColor: '#bf0f0f',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            }

                        }
                    });
                }
            })
        });

        $('#gravar_config_bling').click(function () {
            Swal.fire({
                title: 'Deseja salvar?',
                text: "Certifique-se de ter verificado todos os dados antes de efetuar a ação!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Pode salvar',
                cancelButtonText: "Não, cancele!",
            }).then((result) => {
                if (result.isConfirmed) {
                    var token_bling = $("#token_bling").val();
                    var situacao_separacao = $("#situacao_separacao").val();
                    var situacao_pedido_compra = $("#situacao_pedido_compra").val();
                    var codigo_loja_am = $("#codigo_loja_am").val();
                    var codigo_loja_b2w = $("#codigo_loja_b2w").val();
                    var codigo_loja_ecommerce = $("#codigo_loja_ecommerce").val();
                    var codigo_loja_magalu = $("#codigo_loja_magalu").val();
                    var codigo_loja_ml = $("#codigo_loja_ml").val();
                    var codigo_loja_ms = $("#codigo_loja_ms").val();
                    var codigo_loja_olist = $("#codigo_loja_olist").val();
                    var codigo_loja_shopee = $("#codigo_loja_shopee").val();
                    var codigo_loja_personalizada = $("#codigo_loja_personalizada").val();
                    var estoque_full = $("#estoque_full").val();
                    var loja_full = $("#loja_full").val();



                    $.ajax({
                        type: "POST",
                        url: base_url + '/Integracao/atualizaConfigBling',
                        data: {
                            'token_bling': token_bling,
                            'situacao_separacao': situacao_separacao,
                            'situacao_pedido_compra': situacao_pedido_compra,
                            'codigo_loja_am': codigo_loja_am,
                            'codigo_loja_b2w': codigo_loja_b2w,
                            'codigo_loja_ecommerce': codigo_loja_ecommerce,
                            'codigo_loja_magalu': codigo_loja_magalu,
                            'codigo_loja_ml': codigo_loja_ml,
                            'codigo_loja_ms': codigo_loja_ms,
                            'codigo_loja_olist': codigo_loja_olist,
                            'codigo_loja_shopee': codigo_loja_shopee,
                            'codigo_loja_personalizada': codigo_loja_personalizada,
                            'estoque_full': estoque_full,
                            'loja_full': loja_full
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.msg == "sucesso") {
                                Swal.fire({
                                    title: 'Sucesso',
                                    text: "Dados atualizados com sucesso",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        //window.open('http://sincronizacao.finanplace.com.br/Sincronizacao/sincronizar/'+data.token, '_blank');
                                    }
                                })
                            } else {
                                Swal.fire({
                                    title: 'Erro',
                                    text: "Houve algum erro ao realizar a ação",
                                    icon: 'error',
                                    showCancelButton: false,
                                    confirmButtonColor: '#bf0f0f',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            }

                        }
                    });
                }
            })
        });

        $("#btn_import_csv").click(function () {
            Swal.fire({
                title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                html: '<strong> <h2> Importando anuncios. </br> Isso pode levar alguns minutos, avisaremos quando finalizar! <br><br> Enquanto isso, que tal tomar um cafézinho <i style="color: #783838;" class="icon-xl fas fa-coffee"></i></h2  ></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
            })

            setTimeout(() => {
                $('#frm_import').submit();
            }, 500);

        })


        /**HABILITAR E DESABILITAR MERCADO LIVRE */
        $("#mlenable").hide();
        if ($("#mlinput").val() === '1') {
            $("#mlenable").show();
            $("#mldisable").hide();
        }
        $("#mldisable").click(function () {

            var valor = $("#mkt-1").val();
            var mkts_escolhido =
                '<li id="mercado_livre" class="list-group-item d-flex justify-content-between lh-condensed">' +
                '    <div>' +
                '        <h6 class="my-0">Mercado Livre</h6>' +
                '    </div>' +
                '    <span class="text-muted">R$ ' + valor + '</span>' +
                '</li>';
            $(".produtos").append(mkts_escolhido)
            atualizaTotal(valor)

            $("#mlenable").show();
            $("#mldisable").hide();
            $("#mlinput").val(1)
            /* salvaIntegracoes(); */
        })
        $("#mlenable").click(function () {
            var valor = $("#mkt-1").val();
            atualizaTotal(-valor)
            $("#mercado_livre").remove();
            $("#mlenable").hide();
            $("#mldisable").show();
            $("#mlinput").val(0)
            /* salvaIntegracoes(); */
        })


        /**HABILITAR E DESABILITAR SHOPPE */
        $("#shpenable").hide();
        if ($("#spinput").val() === '1') {
            $("#shpenable").show();
            $("#shpdisable").hide();
        }
        $("#shpdisable").click(function () {

            var valor = $("#mkt-2").val();
            var mkts_escolhido =
                '<li id="shopee" class="list-group-item d-flex justify-content-between lh-condensed">' +
                '    <div>' +
                '        <h6 class="my-0">Shoppe</h6>' +
                '    </div>' +
                '    <span class="text-muted">R$ ' + valor + '</span>' +
                '</li>';
            $(".produtos").append(mkts_escolhido)
            atualizaTotal(valor)
            $("#shpenable").show();
            $("#shpdisable").hide();
            $("#spinput").val(1);
            /* salvaIntegracoes(); */
        })
        $("#shpenable").click(function () {
            $("#shopee").remove();
            var valor = $("#mkt-2").val();
            atualizaTotal(-valor)
            $("#shpenable").hide();
            $("#shpdisable").show();
            $("#spinput").val(0);
            /* salvaIntegracoes(); */
        })


        /**HABILITAR E DESABILITAR OLIST */
        $("#olenable").hide();
        if ($("#olinput").val() === '1') {
            $("#olenable").show();
            $("#oldisable").hide();
        }
        $("#oldisable").click(function () {

            $("#olenable").show();
            $("#oldisable").hide();
            $("#olinput").val(1)
            /* salvaIntegracoes(); */

            var valor = $("#mkt-3").val();
            var mkts_escolhido =
                '<li id="olist" class="list-group-item d-flex justify-content-between lh-condensed">' +
                '    <div>' +
                '        <h6 class="my-0">OLIST</h6>' +
                '    </div>' +
                '    <span class="text-muted">R$ ' + valor + '</span>' +
                '</li>';
            $(".produtos").append(mkts_escolhido)
            atualizaTotal(valor)
        })
        $("#olenable").click(function () {

            $("#olist").remove();
            var valor = $("#mkt-3").val();
            atualizaTotal(-valor)
            $("#olenable").hide();
            $("#oldisable").show();
            $("#olinput").val(0)
            /* salvaIntegracoes(); */
        })


        /**HABILITAR E DESABILITAR MAGALU */
        $("#mgenable").hide();
        if ($("#mginput").val() === '1') {
            $("#mgenable").show();
            $("#mgdisable").hide();
        }
        $("#mgdisable").click(function () {

            var valor = $("#mkt-4").val();
            var mkts_escolhido =
                '<li id="magalu" class="list-group-item d-flex justify-content-between lh-condensed">' +
                '    <div>' +
                '        <h6 class="my-0">Magalu</h6>' +
                '    </div>' +
                '    <span class="text-muted">R$ ' + valor + '</span>' +
                '</li>';
            $(".produtos").append(mkts_escolhido)
            atualizaTotal(valor)
            $("#mgenable").show();
            $("#mgdisable").hide();
            $("#mginput").val(1)
            /* salvaIntegracoes(); */
        })
        $("#mgenable").click(function () {

            $("#magalu").remove();
            var valor = $("#mkt-4").val();
            atualizaTotal(-valor)
            $("#mgenable").hide();
            $("#mgdisable").show();
            $("#mginput").val(0)
            /* salvaIntegracoes(); */
        })


        /**HABILITAR E DESABILITAR B2W */
        $("#b2enable").hide();
        if ($("#b2input").val() === '1') {
            $("#b2enable").show();
            $("#b2disable").hide();
        }
        $("#b2disable").click(function () {

            var valor = $("#mkt-5").val();
            var mkts_escolhido =
                '<li id="b2w" class="list-group-item d-flex justify-content-between lh-condensed">' +
                '    <div>' +
                '        <h6 class="my-0">B2W</h6>' +
                '    </div>' +
                '    <span class="text-muted">R$ ' + valor + '</span>' +
                '</li>';
            $(".produtos").append(mkts_escolhido)
            atualizaTotal(valor)
            $("#b2enable").show();
            $("#b2disable").hide();
            $("#b2input").val(1)
            /* salvaIntegracoes(); */
        })
        $("#b2enable").click(function () {
            $("#b2w").remove();
            var valor = $("#mkt-5").val();
            atualizaTotal(-valor)
            $("#b2enable").hide();
            $("#b2disable").show();
            $("#b2input").val(0)
            /* salvaIntegracoes(); */
        })


        /**HABILITAR E DESABILITAR MERCADO LIVRE */
        $("#amenable").hide();
        if ($("#aminput").val() === '1') {
            $("#amenable").show();
            $("#amdisable").hide();
        }
        $("#amdisable").click(function () {
            var valor = $("#mkt-6").val();
            var mkts_escolhido =
                '<li id="amazon" class="list-group-item d-flex justify-content-between lh-condensed">' +
                '    <div>' +
                '        <h6 class="my-0">Amazon</h6>' +
                '    </div>' +
                '    <span class="text-muted">R$ ' + valor + '</span>' +
                '</li>';
            $(".produtos").append(mkts_escolhido)
            atualizaTotal(valor)
            $("#amenable").show();
            $("#amdisable").hide();
            $("#aminput").val(1)
            /* salvaIntegracoes(); */
        })
        $("#amenable").click(function () {
            $("#amazon").remove();
            var valor = $("#mkt-6").val();
            atualizaTotal(-valor)
            $("#amenable").hide();
            $("#amdisable").show();
            $("#aminput").val(0)
            /* salvaIntegracoes(); */
        })

        function salvaIntegracoes() {
            var id_cliente = $("#id_cliente").val();
            var integracoes = 0;
            var limite = $("#limite_integracoes").val();


            var mercadolivre = $("#mlinput").val();
            if (mercadolivre === "1") { integracoes += 1 }

            var shopee = $("#spinput").val();
            if (shopee === "1") { integracoes += 1 }

            var olist = $("#olinput").val();
            if (olist === "1") { integracoes += 1 }

            var magalu = $("#mginput").val();
            if (magalu === "1") { integracoes += 1 }

            var b2w = $("#b2input").val();
            if (b2w === "1") { integracoes += 1 }

            var amazon = $("#aminput").val();
            if (amazon === "1") { integracoes += 1 }

            if (integracoes > limite) {
                $("#mlenable").hide();
                $("#mldisable").show();
                $("#mlinput").val(0)

                $("#shpenable").hide();
                $("#shpdisable").show();
                $("#spinput").val(0);

                $("#olenable").hide();
                $("#oldisable").show();
                $("#olinput").val(0)

                $("#mgenable").hide();
                $("#mgdisable").show();
                $("#mginput").val(0)

                $("#b2enable").hide();
                $("#b2disable").show();
                $("#b2input").val(0)

                $("#amenable").hide();
                $("#amdisable").show();
                $("#aminput").val(0)

                $.ajax({
                    type: "POST",
                    url: base_url + 'Plano/removeIntegracoes',
                    data: {
                        'id_cliente': id_cliente
                    },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data)

                    }
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Limite de Marketplaces atingido, faça upgrade do plano para continuar! '
                })
            } else {

                $.ajax({
                    type: "POST",
                    url: base_url + 'Plano/setIntegradores',
                    data: {
                        'mercadolivre': mercadolivre,
                        'shopee': shopee,
                        'olist': olist,
                        'magalu': magalu,
                        'b2w': b2w,
                        'amazon': amazon,
                        'id_cliente': id_cliente
                    },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data)

                    }
                });
            }

        }

        $("#att_plano").click(async function () {
            var id_cliente = $("#id_cliente").val();
            var id_plano = $("#id_plano").val();
            const { value: plano } = await Swal.fire({
                title: 'Selecione um plano',
                input: 'select',
                inputOptions: {
                    'Starter Business - R$ 25,00': {
                        1: '1 Marketplaces',
                    },
                    'Professional - R$ 70,00': {
                        2: '3 Marketplaces',
                    },
                    'Premium - R$ 99,00': {
                        3: 'Marketplaces ilimitados',
                    },
                },
                inputPlaceholder: 'Selecione uma opção',
                showCancelButton: true,
                footer: '* Caso seja feito Downgrade, será preciso selecionar os marketplaces novamente!',
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        if (value != '') {
                            resolve()
                        } else {
                            resolve('Campo obrigatório')
                        }
                    })
                }
            })

            if (plano) {
                if (id_plano === plano) {
                    Swal.fire({
                        title: 'Atenção',
                        text: "Plano selecionado ja está aplicado",
                        icon: 'warning',
                        confirmButtonColor: '#3085d6',
                    }).then((result) => {
                        Swal.close()
                    })
                } else {
                    if (plano < id_plano) {
                        $("#mlenable").hide();
                        $("#mldisable").show();
                        $("#mlinput").val(0)

                        $("#shpenable").hide();
                        $("#shpdisable").show();
                        $("#spinput").val(0);

                        $("#olenable").hide();
                        $("#oldisable").show();
                        $("#olinput").val(0)

                        $("#mgenable").hide();
                        $("#mgdisable").show();
                        $("#mginput").val(0)

                        $("#b2enable").hide();
                        $("#b2disable").show();
                        $("#b2input").val(0)

                        $("#amenable").hide();
                        $("#amdisable").show();
                        $("#aminput").val(0)

                        $.ajax({
                            type: "POST",
                            url: base_url + 'Plano/removeIntegracoes',
                            data: {
                                'id_cliente': id_cliente
                            },
                            dataType: 'json',
                            success: function (data) {
                                console.log(data)

                            }
                        });
                    }
                    $.ajax({
                        type: "POST",
                        url: base_url + 'Plano/atualizaPlano',
                        data: {
                            'id_plano': plano,
                            'id_cliente': id_cliente
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.msg === "sucesso") {
                                Swal.fire({
                                    title: 'Sucesso',
                                    text: "Plano atualizado",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            }

                        }
                    });
                }



            }
        })

        $(".opcoes_marketplaces").hide()
        $(".plano_mkt").click(function () {
            var id = $(this).attr('id');
            $("#plano_escolhido").val(id)

            if (id != undefined && id != "") {
                $.ajax({
                    type: "POST",
                    url: base_url + 'Plano/getIntegradoresPlano',
                    data: {
                        'id_plano': id
                    },
                    dataType: 'json',
                    success: function (data) {
                        $(".opcoes_marketplaces").show()
                        $(".produtos").empty()
                        $(".total_checkout").remove()
                        var novo_valor = '<span class="text-muted total_checkout">R$ 0,00</span>'
                        $(".li-total").append(novo_valor)
                        var cabecalho =
                            '<li class="list-group-item d-flex justify-content-between lh-condensed">' +
                            '    <div>' +
                            '        <h5 class="my-0">Plano escolhido</h5>' +
                            '    </div>' +
                            '</li>';
                        $(".produtos").append(cabecalho)
                        var plano_escolhido =
                            '<li class="list-group-item d-flex justify-content-between lh-condensed">' +
                            '    <div>' +
                            '        <h6 class="my-0">' + data.plano.DESCRICAO_PLANO + '</h6>' +
                            '    </div>' +
                            '    <span class="text-muted">R$ ' + data.plano.VALOR + '</span>' +
                            '</li>';
                        $(".produtos").append(plano_escolhido)
                        atualizaTotal(data.plano.VALOR)
                        if (data.integradores != undefined) {
                            $("#mlenable").hide();
                            $("#mldisable").show();
                            $("#mlinput").val(0)
                            $("#mlenable").css('pointer-events', 'auto');

                            $("#shpenable").hide();
                            $("#shpdisable").show();
                            $("#spinput").val(0);
                            $("#shpenable").css('pointer-events', 'auto');

                            $("#olenable").hide();
                            $("#oldisable").show();
                            $("#olinput").val(0)
                            $("#olenable").css('pointer-events', 'auto');

                            $("#mgenable").hide();
                            $("#mgdisable").show();
                            $("#mginput").val(0)
                            $("#mgenable").css('pointer-events', 'auto');

                            $("#b2enable").hide();
                            $("#b2disable").show();
                            $("#b2input").val(0)
                            $("#b2enable").css('pointer-events', 'auto');

                            $("#amenable").hide();
                            $("#amdisable").show();
                            $("#aminput").val(0)
                            $("#amenable").css('pointer-events', 'auto');
                            var array_integradores = []
                            for (let index = 0; index < data.integradores.length; index++) {
                                array_integradores.push(data.integradores[index].FK_ID_INTEGRADOR)
                                if (data.integradores[index].FK_ID_INTEGRADOR == "1") {
                                    $("#mlenable").show();
                                    $("#mldisable").hide();
                                    $("#mlinput").val(1)
                                    $("#mlenable").css('pointer-events', 'none');
                                } else if (data.integradores[index].FK_ID_INTEGRADOR == "2") {
                                    $("#shpenable").show();
                                    $("#shpdisable").hide();
                                    $("#spinput").val(1);
                                    $("#shpenable").css('pointer-events', 'none');
                                } else if (data.integradores[index].FK_ID_INTEGRADOR == "3") {
                                    $("#olenable").show();
                                    $("#oldisable").hide();
                                    $("#olinput").val(1)
                                    $("#olenable").css('pointer-events', 'none');
                                } else if (data.integradores[index].FK_ID_INTEGRADOR == "4") {
                                    $("#mgenable").show();
                                    $("#mgdisable").hide();
                                    $("#mginput").val(1)
                                    $("#mgenable").css('pointer-events', 'none');
                                } else if (data.integradores[index].FK_ID_INTEGRADOR == "5") {
                                    $("#b2enable").show();
                                    $("#b2disable").hide();
                                    $("#b2input").val(1)
                                    $("#b2enable").css('pointer-events', 'none');
                                } else if (data.integradores[index].FK_ID_INTEGRADOR == "6") {
                                    $("#amenable").show();
                                    $("#amdisable").hide();
                                    $("#aminput").val(1)
                                    $("#amenable").css('pointer-events', 'none');
                                }


                            }
                            $("#integradores_plano").val(array_integradores)
                        }

                    }
                });
            } else {
                $("#mlenable").hide();
                $("#mldisable").show();
                $("#mlinput").val(0)
                $("#mlenable").css('pointer-events', 'auto');

                $("#shpenable").hide();
                $("#shpdisable").show();
                $("#spinput").val(0);
                $("#shpenable").css('pointer-events', 'auto');

                $("#olenable").hide();
                $("#oldisable").show();
                $("#olinput").val(0)
                $("#olenable").css('pointer-events', 'auto');

                $("#mgenable").hide();
                $("#mgdisable").show();
                $("#mginput").val(0)
                $("#mgenable").css('pointer-events', 'auto');

                $("#b2enable").hide();
                $("#b2disable").show();
                $("#b2input").val(0)
                $("#b2enable").css('pointer-events', 'auto');

                $("#amenable").hide();
                $("#amdisable").show();
                $("#aminput").val(0)
                $("#amenable").css('pointer-events', 'auto');

            }

        })

        $("#qtd_conta_ml").on('change', function () {


            var plano_escolhido = $("#plano_escolhido").val();
            var valor_plano = 0;
            var qtd_contas = $(this).val();


            $.ajax({
                type: "POST",
                url: base_url + 'Plano/getDadosPlanoEscolhido',
                data: {
                    id_plano: plano_escolhido,
                    marktplaces: ''
                },
                dataType: 'json',
                success: function (data) {
                    if (data != undefined) {

                        valor_plano = data.plano.VALOR;

                        if (qtd_contas > 1) {
                            var valor_contas = 5 * qtd_contas;


                            var valor_atual = parseFloat(valor_plano) + valor_contas - 5;

                            $(".total_checkout").remove()
                            valor_atual = valor_atual
                            var novo_valor = '<span class="text-muted total_checkout">R$ ' + valor_atual.toFixed(2) + '</span>'
                            $(".ul-conta_ml").show()
                            $(".li-total").append(novo_valor)


                        } else {

                            valor_atual = parseFloat(valor_plano);
                            $(".total_checkout").remove()
                            valor_atual = valor_atual
                            var novo_valor = '<span class="text-muted total_checkout">R$ ' + valor_atual.toFixed(2) + '</span>'
                            $(".ul-conta_ml").show()
                            $(".li-total").append(novo_valor)
                        }

                    }

                }
            });




        })

        $("#salvar_plano").click(async function () {
            var plano = ""
            var plano_escolhido = $("#plano_escolhido").val();
            var array_marketplaces = []
            var array_integradores = $("#integradores_plano").val()
            array_integradores = array_integradores.match(/\d+/g)
            if ($("#mlinput").val() == 1) {
                array_marketplaces.push('1')
            }
            if ($("#spinput").val() == 1) {
                array_marketplaces.push('2')
            }
            if ($("#olinput").val() == 1) {
                array_marketplaces.push('3')
            }
            if ($("#mginput").val() == 1) {
                array_marketplaces.push('4')
            }
            if ($("#b2input").val() == 1) {
                array_marketplaces.push('5')
            }
            if ($("#aminput").val() == 1) {
                array_marketplaces.push('6')
            }


            let unique1 = array_marketplaces.filter((o) => array_integradores.indexOf(o) === -1);
            let unique2 = array_integradores.filter((o) => array_marketplaces.indexOf(o) === -1);

            const marketplaces_escolhidos = unique1.concat(unique2);

            //console.log(marketplaces_escolhidos);

            var qtd_contas = $("#qtd_conta_ml").val();

            $.ajax({
                type: "POST",
                url: base_url + 'Plano/getDadosPlanoEscolhido',
                data: {
                    id_plano: plano_escolhido,
                    marktplaces: marketplaces_escolhidos
                },
                dataType: 'json',
                success: function (data) {
                    if (data != undefined) {
                        var adicionais = ""
                        var total = 0
                        if (data.markets != "") {
                            for (let index = 0; index < data.markets.length; index++) {
                                adicionais += "<h4> Marketplaces adicionados <br>" + data.markets[index].DESCRICAO_INTEGRADOR + " - R$" + data.markets[index].VALOR_UNITARIO.replace(".", ",") + "<br></h4>"
                                total += parseFloat(data.markets[index].VALOR_UNITARIO)
                            }

                        }

                        console.log(data)

                        plano = "<h3>" + data.plano.DESCRICAO_PLANO + " - R$" + data.plano.VALOR.replace(".", ",") + "</h3>";
                        total += parseFloat(data.plano.VALOR)

                        var contas_adicionais_ml = ""
                        var valor_contas = 0;
                        if (qtd_contas > 1) {
                            valor_contas = 5 * qtd_contas;
                            valor_contas = valor_contas - 5;
                            qtd_contas = qtd_contas - 1

                            var contas_adicionais_ml = '<strong>Contas adicionais Mercado Livre: ' + qtd_contas + ' - R$ ' + valor_contas + ' <br></strong>'


                        }

                        total += parseFloat(valor_contas)

                        var total_valor = total
                        total = total.toString()



                        total = '<h2>Total: R$ ' + total + '</h2>'

                        var input_cupom = '<strong> Caso possuá algum cupom de desconto, basta informar abaixo</strong><br> <input type="text" id="input_cupom"  >'
                        var obs = "*Formas de pagamentos permitidas: Cartão de crédito e mercado pago"


                        Swal.fire({
                            title: 'As informações do plano selecionado estão corretas ?',
                            html: plano + contas_adicionais_ml + "<br>" + total.replace(".", ",") + input_cupom + "<br><br>" + obs,
                            icon: 'warning',
                            width: 600,
                            heightAuto: true,
                            showDenyButton: true,
                            confirmButtonText: 'Salvar',
                            denyButtonText: `Não salvar!`,
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                var valor_cupom = $("#input_cupom").val();

                                if (valor_cupom != "") {
                                    $.ajax({
                                        type: "POST",
                                        url: base_url + 'Plano/getValidaCupom',
                                        data: {
                                            cupom: valor_cupom,
                                            id_plano: plano_escolhido,
                                        },
                                        dataType: 'json',
                                        success: function (data) {
                                            if (data != undefined && data != "") {
                                                var desconto_cupom = data.VALOR_DESCONTO;
                                                Swal.fire({
                                                    title: 'Sucesso',
                                                    text: "Cupom válido, será aplicado um desconto de R$" + desconto_cupom.replace(".", ","),
                                                    icon: 'success',
                                                    showCancelButton: false,
                                                    confirmButtonColor: '#3085d6',
                                                    confirmButtonText: 'OK!'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                    }
                                                })
                                                $.ajax({
                                                    type: "POST",
                                                    url: base_url + 'Plano/setPlanoEscolhido',
                                                    data: {
                                                        total_valor: total_valor,
                                                        id_plano: plano_escolhido,
                                                        marktplaces: marketplaces_escolhidos,
                                                        valor_desconto: desconto_cupom,
                                                        qtd_contas_adc: qtd_contas
                                                    },
                                                    dataType: 'json',
                                                    success: function (data) {
                                                        console.log(data.response)
                                                        if (data.response != undefined) {
                                                            var dados = JSON.parse(data.response)
                                                            window.open(dados.init_point, '_blank');
                                                            Swal.fire({
                                                                title: 'Atenção',
                                                                text: "Realize o pagamento da assinatura para utilizar a plataforma",
                                                                icon: 'warning',
                                                                showCancelButton: false,
                                                                confirmButtonColor: '#3085d6',
                                                                confirmButtonText: 'OK!'
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    //window.open('http://sincronizacao.finanplace.com.br/Sincronizacao/sincronizarGeral/'+data.token, '_blank');
                                                                }
                                                            })

                                                        }

                                                    }
                                                });

                                            } else {
                                                Swal.fire({
                                                    title: 'Atenção',
                                                    text: "Cupom inválido!",
                                                    icon: 'warning',
                                                    showCancelButton: false,
                                                    confirmButtonColor: '#3085d6',
                                                    confirmButtonText: 'OK!'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                    }
                                                })
                                            }

                                        }
                                    });

                                } else {
                                    $.ajax({
                                        type: "POST",
                                        url: base_url + 'Plano/setPlanoEscolhido',
                                        data: {
                                            total_valor: total_valor,
                                            id_plano: plano_escolhido,
                                            marktplaces: marketplaces_escolhidos,
                                            qtd_contas_adc: qtd_contas
                                        },
                                        dataType: 'json',
                                        success: function (data) {
                                            if (data.response != undefined) {
                                                var dados = JSON.parse(data.response)
                                                window.open(dados.init_point, '_blank');
                                                Swal.fire({
                                                    title: 'Atenção',
                                                    text: "Realize o pagamento da assinatura para utilizar a plataforma",
                                                    icon: 'warning',
                                                    showCancelButton: false,
                                                    confirmButtonColor: '#3085d6',
                                                    confirmButtonText: 'OK!'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        //window.open('http://sincronizacao.finanplace.com.br/Sincronizacao/sincronizarGeral/'+data.token, '_blank');
                                                    }
                                                })

                                            }

                                        }
                                    });
                                }
                            } else if (result.isDenied) {
                                Swal.fire('Assinatura não confirmada', '', 'info')
                            }
                        })
                    }

                }
            });


        })

        if ($("#nova_conexao").val() == "S") {
            var id_conta_ml = $("#id_conta_ml").val()

            Swal.fire({
                title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                html: '<strong> <h2> Sincronizando vendas. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2  ></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
            })

            $('#frm_pesquisa').submit();

            setTimeout(() => {
                Swal.fire({
                    title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguardar só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                })

            }, 50000);

            setTimeout(() => {
                Swal.fire({
                    title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguardar só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                })

            }, 90000);
            $.ajax({
                type: "POST",
                url: base_url + 'Dashboard/getHistoricoVendas',
                data: {
                    'id_conta_ml': id_conta_ml
                },
                dataType: 'json',
                success: function (data) {
                    if (data.qtd_vendas > 0) {
                        Swal.fire({
                            title: 'Sucesso',
                            text: "Dados sincronizadas com sucesso!",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "/Integracao/consultarIntegracao?tipo_msg=sucesso&msg=Ação realizada!");
                            }
                        })
                    } else {
                        Swal.fire({
                            title: 'Atenção',
                            text: "Nenhuma venda encontrada",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "/Integracao/consultarIntegracao?tipo_msg=sucesso&msg=Ação realizada!");
                            }
                        })
                    }

                }
            });
        }

        $("#desconectar_ml").on('click', function () {
            var id_conta_ml = $("#id_conta_ml").val()
            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/removeIntegracaoML',
                data: {
                    'id_conta_ml': id_conta_ml
                },
                dataType: 'json',
                success: function (data) {
                    if (data.qtd_vendas > 0) {
                        Swal.fire({
                            title: 'Sucesso',
                            text: "Conta removida com sucesso!",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "Integracao/consultarIntegracao?tipo_msg=sucesso&msg=Ação realizada!");
                            }
                        })
                    } else {
                        Swal.fire({
                            title: 'Atenção',
                            text: "Nenhuma venda encontrada",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "Integracao/consultarIntegracao?tipo_msg=sucesso&msg=Ação realizada!");
                            }
                        })
                    }

                }
            });
        })

        /* $('.percentual-imposto').maskMoney({

        suffix: ' %',
        affixesStay: false
        
        
        }); */

        var base_url = window.location.origin + "/";
        var datatable_vendas = "";
        var resposta = ""

        $("#sincronizar_bling").click(function () {
            Swal.fire({
                title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                html: '<strong> <h2> Sincronizando dados. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
            })
            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/getDadosBling',
                data: {},
                dataType: 'json',
                success: function (data) {
                    if (data.qtd_vendas > 0) {
                        Swal.fire({
                            title: 'Sucesso',
                            text: "Dados sincronizados com sucesso!",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "Integracao/sincronizaProdutos?tipo_msg=sucesso&msg=Dados sincronizados!");
                            }
                        })
                    } else {
                        Swal.fire({
                            title: 'Atenção',
                            text: "Nenhum dado encontrado, verifique as configurações em Bling -> Habilitar Integração",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "Integracao/sincronizaProdutos");
                            }
                        })
                    }

                }
            });
            setTimeout(() => {
                Swal.fire({
                    title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguardar só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                })

            }, 50000);

            setTimeout(() => {
                Swal.fire({
                    title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguardar só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                })

            }, 90000);
        })

        $("#sincronizar_produtos_recentes").click(function () {
            Swal.fire({
                title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                html: '<strong> <h2> Sincronizando dados. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
            })
            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/getProdutosRecentes',
                data: {},
                dataType: 'json',
                success: function (data) {
                    if (data.qtd_vendas > 0) {
                        Swal.fire({
                            title: 'Sucesso',
                            text: "Dados sincronizados com sucesso!",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "Integracao/sincronizaProdutos?tipo_msg=sucesso&msg=Dados sincronizados!");
                            }
                        })
                    } else {
                        Swal.fire({
                            title: 'Atenção',
                            text: "Nenhum dado encontrado",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "Integracao/sincronizaProdutos?tipo_msg=sucesso&msg=Nenhum dado encontrado!");
                            }
                        })
                    }

                }
            });
            setTimeout(() => {
                Swal.fire({
                    title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                })

            }, 50000);

            setTimeout(() => {
                Swal.fire({
                    title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                })

            }, 90000);
        })

        $("#sincronizar_vendas_recentes").click(function () {
            Swal.fire({
                title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                html: '<strong> <h2> Sincronizando dados. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
            })
            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/getVendasRecentes',
                data: {},
                dataType: 'json',
                success: function (data) {
                    if (data.qtd_vendas > 0) {
                        Swal.fire({
                            title: 'Sucesso',
                            text: "Dados sincronizados com sucesso!",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "Integracao/sincronizaProdutos?tipo_msg=sucesso&msg=Dados sincronizados!");
                            }
                        })
                    } else {
                        Swal.fire({
                            title: 'Atenção',
                            text: "Nenhum dado encontrado",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "Integracao/sincronizaProdutos?tipo_msg=sucesso&msg=Nenhum dado encontrado!");
                            }
                        })
                    }

                }
            });
            setTimeout(() => {
                Swal.fire({
                    title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                })

            }, 50000);

            setTimeout(() => {
                Swal.fire({
                    title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                })

            }, 90000);
        })

        $("#sincronizar_pedidos_recentes").click(function () {
            Swal.fire({
                title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                html: '<strong> <h2> Sincronizando dados. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
            })
            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/getPedidosRecentes',
                data: {},
                dataType: 'json',
                success: function (data) {
                    console.log("data")
                    console.log(data)
                    if (data.qtd_vendas > 0) {
                        Swal.fire({
                            title: 'Sucesso',
                            text: "Dados sincronizados com sucesso!",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        })
                    } else {
                        Swal.fire({
                            title: 'Atenção',
                            text: "Nenhum dado encontrado",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();

                            }
                        })
                    }

                }
            });
            setTimeout(() => {
                Swal.fire({
                    title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                })

            }, 50000);

            setTimeout(() => {
                Swal.fire({
                    title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                })

            }, 90000);
        })

        $("#sincronizar_pedidos_separacao").click(function () {
            Swal.fire({
                title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                html: '<strong> <h2> Sincronizando dados. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
            })
            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/getPedidosSeparacao',
                data: {},
                dataType: 'json',
                success: function (data) {
                    console.log("data")
                    console.log(data)
                    if (data.qtd_vendas > 0) {
                        Swal.fire({
                            title: 'Sucesso',
                            text: "Dados sincronizados com sucesso!",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "Integracao/blingSeparação?tipo_msg=sucesso&msg=Dados sincronizados!");
                            }
                        })
                    } else {
                        Swal.fire({
                            title: 'Atenção',
                            text: "Nenhum dado encontrado",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "Integracao/blingSeparação?tipo_msg=sucesso&msg=Nenhum dado encontrado!");
                            }
                        })
                    }

                }
            });
            setTimeout(() => {
                Swal.fire({
                    title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                })

            }, 50000);

            setTimeout(() => {
                Swal.fire({
                    title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                })

            }, 90000);
        })

        $("#sincronizar_dados_full").click(function () {
            Swal.fire({
                title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                html: '<strong> <h2> Sincronizando dados. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
            })


            const settings = {
                "async": true,
                "crossDomain": true,
                "url": "https://finanplace.com.br:3001/getFulfillment",
                "method": "POST",
                "headers": {
                    "Content-Type": "application/json"
                },
                "processData": false,
                "data": "{\n\t\"token_acesso\": \"APP_USR-5503900093812594-080723-57d3587ff31c75cabcd14b7d3b497e6f-579922797\",\n\t\"refresh_token\": \"TG-64cd30c68430990001dede84-579922797\",\n\t\"codigo_conta\": \"TG-64cd30c5dd9ab700015dd743-579922797\",\n\t\"clientId\": \"5503900093812594\",\n\t\"clientSecret\": \"ZuiY1Cycqww0S8LHB693MQv2iqr8sGiI\",\n\t\"id_mercado_livre\": \"100\",\n\t\"id_cliente\": \"1\"\n}"
            };

            $.ajax(settings).done(function (response) {
                console.log(response);
            });

            setTimeout(() => {
                Swal.fire({
                    title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                })

            }, 90000);
        })

        $(".btn-conta-ml").click(function () {
            var id = $(this).attr('id')
            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/setContaML',
                data: {
                    id_conta_ml: id
                },
                dataType: 'json',
                success: function (data) {
                    if (data.qtd_vendas > 0) {
                        Swal.fire({
                            title: 'Sucesso',
                            text: "Dados sincronizados com sucesso!",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "Integracao/sincronizaProdutos?tipo_msg=sucesso&msg=Dados sincronizados!");
                            }
                        })
                    } else {
                        Swal.fire({
                            title: 'Atenção',
                            text: "Nenhum dado encontrado",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "Integracao/sincronizaProdutos?tipo_msg=sucesso&msg=Nenhum dado encontrado!");
                            }
                        })
                    }

                }
            });
            //https://auth.mercadolivre.com.br/authorization?response_type=code&client_id=5503900093812594&redirect_uri=https://app.finanplace.com.br/Integracao/integracaoMercadoLivre
        })

        $("#sincronizar_bling_geral").click(function () {
            Swal.fire({
                title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                html: '<strong> <h2> Sincronizando dados. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
            })
            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/getDadosRecentes',
                data: {},
                dataType: 'json',
                success: function (data) {
                    if (data.qtd_vendas > 0) {
                        Swal.fire({
                            title: 'Sucesso',
                            text: "Dados sincronizados com sucesso!",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "Integracao/sincronizaProdutos?tipo_msg=sucesso&msg=Dados sincronizados!");
                            }
                        })
                    } else {
                        Swal.fire({
                            title: 'Atenção',
                            text: "Nenhum dado encontrado",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "Integracao/sincronizaProdutos?tipo_msg=sucesso&msg=Nenhum dado encontrado!");
                            }
                        })
                    }

                }
            });
            setTimeout(() => {
                Swal.fire({
                    title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                })

            }, 50000);

            setTimeout(() => {
                Swal.fire({
                    title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                })

            }, 90000);
        })

        //$("#fornecedores").select2();
        //Initialize Select2 Elements
        console.log("initialize select2 elements")
        $('.select2').select2();

        $("#atualizar_filtro").click(function () {

            Swal.fire({
                title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                html: '<strong> <h2> Sincronizando dados. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2  ></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
            })
            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/atualizaInfos',
                data: {},
                dataType: 'json',
                success: function (data) {
                    if (data.qtd_vendas > 0) {
                        Swal.fire({
                            title: 'Sucesso',
                            text: "Dados sincronizados com sucesso!",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "Integracao/integracaoBling?tipo_msg=sucesso&msg=Ação realizada!");
                            }
                        })
                    } else {
                        Swal.fire({
                            title: 'Atenção',
                            text: "Nenhum dado encontrado",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "Integracao/integracaoBling?tipo_msg=sucesso&msg=Ação realizada!");
                            }
                        })
                    }

                }
            });

        })

        $("#atualizar_pedidos_venda").click(function () {

            Swal.fire({
                title: '<div  style="overflow: hidden;width: 10rem; height: 10rem;" class="spinner-border text-secondary" role="status"> </div>',
                html: '<strong> <h2> Sincronizando dados. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2  ></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
            })
            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/atualizaPedidos',
                data: {},
                dataType: 'json',
                success: function (data) {
                    if (data.qtd_vendas > 0) {
                        Swal.fire({
                            title: 'Sucesso',
                            text: "Dados sincronizados com sucesso!",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "Integracao/blingSeparação?tipo_msg=sucesso&msg=Ação realizada!");
                            }
                        })
                    } else {
                        Swal.fire({
                            title: 'Atenção',
                            text: "Nenhum dado encontrado",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.replace(base_url + "Integracao/blingSeparação?tipo_msg=sucesso&msg=Ação realizada!");
                            }
                        })
                    }

                }
            });

        })


        var mercadorias_bling = $('#mercadorias_bling').DataTable({
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
                    "copy": "Copiar",
                    "print": "Imprimir",
                    "colvis": "Colunas"
                },
            },
            "pageLength": 100,
            scrollX: true,
            //scrollY: 500,
            "info": true,
            select: "multi",
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    className: 'botao_export',
                    pageSize: 'LEGAL',
                    footer: true
                },
                { extend: 'csv', className: 'botao_export', footer: true },
                { extend: 'excel', className: 'botao_export', footer: true },
                { extend: 'colvis', className: 'botao_export', },
                {
                    text: 'Desmarcar todos',
                    className: 'botao_export',
                    action: function () {
                        dados_bling.rows().deselect();
                    }
                },
            ],
        });


        var mercadorias_bling = $('#planos').DataTable({
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
                    "copy": "Copiar",
                    "print": "Imprimir",
                    "colvis": "Colunas"
                },
            },
            "pageLength": 100,
            scrollX: true,
            //scrollY: 500,
            "info": true,
            select: "multi",
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    className: 'botao_export',
                    pageSize: 'LEGAL',
                    footer: true
                },
                { extend: 'csv', className: 'botao_export', footer: true },
                { extend: 'excel', className: 'botao_export', footer: true },
                { extend: 'colvis', className: 'botao_export', },
                {
                    text: 'Desmarcar todos',
                    className: 'botao_export',
                    action: function () {
                        dados_bling.rows().deselect();
                    }
                },
            ],
        });

        var mercadorias_bling = $('#cupom').DataTable({
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
                    "copy": "Copiar",
                    "print": "Imprimir",
                    "colvis": "Colunas"
                },
            },
            "pageLength": 100,
            scrollX: true,
            //scrollY: 500,
            "info": true,
            select: "multi",
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    className: 'botao_export',
                    pageSize: 'LEGAL',
                    footer: true
                },
                { extend: 'csv', className: 'botao_export', footer: true },
                { extend: 'excel', className: 'botao_export', footer: true },
                { extend: 'colvis', className: 'botao_export', },
                {
                    text: 'Desmarcar todos',
                    className: 'botao_export',
                    action: function () {
                        dados_bling.rows().deselect();
                    }
                },
            ],
        });

        $("#codigo_sku").focus()

        $("#codigo_sku").blur(function () {
            $("#codigo_sku").focus()
        })

        document.querySelector('#codigo_sku').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                var codigo = $("#codigo_sku").val()

                if (codigo != "") {
                    var resposta = ""

                    $.ajax({
                        type: "POST",
                        url: base_url + 'Integracao/getProdutoMercadoria',
                        data: {
                            "codigo": codigo
                        },
                        async: false,
                        dataType: 'json',
                        success: function (data) {

                            if (data != "" && data != undefined) {
                                $("#corpo_tabela").empty()
                                var corpo_tabela = "";
                                for (let index = 0; index < data.length; index++) {
                                    corpo_tabela +=
                                        "<tr style='height: 100px;' >" +
                                        "<td>" + data[index].CODIGO_FABRICANTE + "</td>" +
                                        "<td style='white-space: nowrap;' >" + data[index].DESCRICAO_PRODUTO + "</td>" +
                                        "<td>" + data[index].CODIGO_PRODUTO + "</td>" +
                                        "<td style='font-size: 16px' ><strong>" + data[index].LOCALIZACAO + "</strong></td>" +
                                        "<td>" + data[index].LIDOS + "</td>" +
                                        "<td style='text-align: center;' > <a href='/Integracao/atualizaLido/" + data[index].ID_TMP_PRODUTO_BLING + "/aumentar'><i style='color: #b30000' class='fa fa-plus'></i></a>  <a href='/Integracao/atualizaLido/" + data[index].ID_TMP_PRODUTO_BLING + "/reduzir'><i style='color: #003ba8' class='fa fa-minus'></i></a></td>" +
                                        "</tr>";
                                }

                                $("#corpo_tabela").append(corpo_tabela)
                                $("#codigo_sku").val("")
                                $("#codigo_sku").focus()
                            }

                        }
                    });
                }
            }
        });


        function atualizaTotal(valor) {
            var valor_mmkt = parseFloat(valor)
            var valor_atual = $(".total_checkout").html()
            console.log(typeof valor_atual);
            if (typeof valor_atual === 'string') {
                valor_atual = valor_atual.split(" ")
                valor_atual = valor_atual[1].replace(",", ".")
                valor_atual = parseFloat(valor_atual)
                valor_atual = valor_atual + valor_mmkt;
                $(".total_checkout").remove()
                valor_atual = valor_atual
                var novo_valor = '<span class="text-muted total_checkout">R$ ' + valor_atual.toFixed(2) + '</span>'
                $(".ul-conta_ml").show()
                $(".li-total").append(novo_valor)
            }
        }
    };

    var initTable2 = function () {
        var table = $('#kt_datatable2');

        // begin second table
        table.DataTable({
            scrollY: '50vh',
            scrollX: true,
            scrollCollapse: true,
            createdRow: function (row, data, index) {
                var status = {
                    1: {
                        'title': 'Pending',
                        'class': 'label-light-primary'
                    },
                    2: {
                        'title': 'Delivered',
                        'class': ' label-light-danger'
                    },
                    3: {
                        'title': 'Canceled',
                        'class': ' label-light-primary'
                    },
                    4: {
                        'title': 'Success',
                        'class': ' label-light-success'
                    },
                    5: {
                        'title': 'Info',
                        'class': ' label-light-info'
                    },
                    6: {
                        'title': 'Danger',
                        'class': ' label-light-danger'
                    },
                    7: {
                        'title': 'Warning',
                        'class': ' label-light-warning'
                    },
                };
                var badge = '<span class="label ' + status[data[18]].class + ' label-inline label-bold">' + status[data[18]].title + '</span>';
                row.getElementsByTagName('td')[18].innerHTML = badge;

                status = {
                    1: {
                        'title': 'Online',
                        'state': 'danger'
                    },
                    2: {
                        'title': 'Retail',
                        'state': 'primary'
                    },
                    3: {
                        'title': 'Direct',
                        'state': 'success'
                    },
                };
                badge = '<span class="label label-' + status[data[19]].state + ' label-dot mr-2"></span>' +
                    '<span class="font-weight-bold text-' + status[data[19]].state + '">' + status[data[19]].title + '</span>';
                row.getElementsByTagName('td')[19].innerHTML = badge;
            },
            columnDefs: [{
                targets: -1,
                title: 'Actions',
                orderable: false,
                width: '125px',
                render: function (data, type, full, meta) {
                    return '\
	                        <div class="dropdown dropdown-inline">\
	                            <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">\
	                                <span class="svg-icon svg-icon-md">\
	                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
	                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
	                                            <rect x="0" y="0" width="24" height="24"/>\
	                                            <path d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z" fill="#000000"/>\
	                                        </g>\
	                                    </svg>\
	                                </span>\
	                            </a>\
	                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">\
	                                <ul class="navi flex-column navi-hover py-2">\
	                                    <li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">\
	                                        Choose an action:\
	                                    </li>\
	                                    <li class="navi-item">\
	                                        <a href="#" class="navi-link">\
	                                            <span class="navi-icon"><i class="la la-print"></i></span>\
	                                            <span class="navi-text">Print</span>\
	                                        </a>\
	                                    </li>\
	                                    <li class="navi-item">\
	                                        <a href="#" class="navi-link">\
	                                            <span class="navi-icon"><i class="la la-copy"></i></span>\
	                                            <span class="navi-text">Copy</span>\
	                                        </a>\
	                                    </li>\
	                                    <li class="navi-item">\
	                                        <a href="#" class="navi-link">\
	                                            <span class="navi-icon"><i class="la la-file-excel-o"></i></span>\
	                                            <span class="navi-text">Excel</span>\
	                                        </a>\
	                                    </li>\
	                                    <li class="navi-item">\
	                                        <a href="#" class="navi-link">\
	                                            <span class="navi-icon"><i class="la la-file-text-o"></i></span>\
	                                            <span class="navi-text">CSV</span>\
	                                        </a>\
	                                    </li>\
	                                    <li class="navi-item">\
	                                        <a href="#" class="navi-link">\
	                                            <span class="navi-icon"><i class="la la-file-pdf-o"></i></span>\
	                                            <span class="navi-text">PDF</span>\
	                                        </a>\
	                                    </li>\
	                                </ul>\
	                            </div>\
	                        </div>\
	                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit details">\
	                            <span class="svg-icon svg-icon-md">\
	                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
	                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
	                                        <rect x="0" y="0" width="24" height="24"/>\
	                                        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero"\ transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>\
	                                        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>\
	                                    </g>\
	                                </svg>\
	                            </span>\
	                        </a>\
	                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon" title="Delete">\
	                            <span class="svg-icon svg-icon-md">\
	                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
	                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
	                                        <rect x="0" y="0" width="24" height="24"/>\
	                                        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>\
	                                        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>\
	                                    </g>\
	                                </svg>\
	                            </span>\
	                        </a>\
	                    ';
                },
            },
            {
                targets: 8,
                width: '75px',
                render: function (data, type, full, meta) {
                    var status = {
                        1: {
                            'title': 'Pending',
                            'class': 'label-light-primary'
                        },
                        2: {
                            'title': 'Delivered',
                            'class': ' label-light-danger'
                        },
                        3: {
                            'title': 'Canceled',
                            'class': ' label-light-primary'
                        },
                        4: {
                            'title': 'Success',
                            'class': ' label-light-success'
                        },
                        5: {
                            'title': 'Info',
                            'class': ' label-light-info'
                        },
                        6: {
                            'title': 'Danger',
                            'class': ' label-light-danger'
                        },
                        7: {
                            'title': 'Warning',
                            'class': ' label-light-warning'
                        },
                    };
                    if (typeof status[data] === 'undefined') {
                        return data;
                    }
                    return '<span class="label ' + status[data].class + ' label-inline label-bold">' + status[data].title + '</span>';
                },
            },
            {
                targets: 9,
                width: '75px',
                render: function (data, type, full, meta) {
                    var status = {
                        1: {
                            'title': 'Online',
                            'state': 'danger'
                        },
                        2: {
                            'title': 'Retail',
                            'state': 'primary'
                        },
                        3: {
                            'title': 'Direct',
                            'state': 'success'
                        },
                    };
                    if (typeof status[data] === 'undefined') {
                        return data;
                    }
                    return '<span class="label label-' + status[data].state + ' label-dot mr-2"></span>' +
                        '<span class="font-weight-bold text-' + status[data].state + '">' + status[data].title + '</span>';
                },
            },
            ],
        });
    };

    return {

        //main function to initiate the module
        init: function () {
            initTable1();
            initTable2();
        },

    };

}();

jQuery(document).ready(function () {
    KTDatatablesBasicScrollable.init();
});

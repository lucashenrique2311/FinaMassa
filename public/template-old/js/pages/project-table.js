//[Data Table Javascript]


$(function () {
    "use strict";

    $(document).ready(function () {

        if ($(".custom-tooltip").length === 0) {
            $("body").append('<div  class="tooltip-custom custom-tooltip"></div>');
        }

        var base_url = window.location.origin + "/";

        $("#imprimir_separacao").click(function () {
            // Altera o action do form
            $("#frm_pesquisa").attr("action", "/Integracao/imprimirSeparacao");

            // Altera o target para abrir em uma nova aba
            $("#frm_pesquisa").attr("target", "_blank");

            // Submete o form
            $("#frm_pesquisa").submit();
        });

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

        $('.inputKg').on('input', function () {
            let value = $(this).val().replace(/[^0-9.]/g, ''); // Remove tudo que não for número ou ponto
            $(this).val(value + ' kg'); // Adiciona 'kg' no final
        }).on('focus', function () {
            let value = $(this).val().replace(' kg', ''); // Remove 'kg' ao focar
            $(this).val(value);
        }).on('blur', function () {
            let value = $(this).val().replace(/[^0-9.]/g, ''); // Remove caracteres inválidos ao perder foco
            if (value) {
                $(this).val(value + ' kg'); // Adiciona 'kg' novamente
            }
        });


        $('.inputCm').on('input', function () {
            let value = $(this).val().replace(/[^0-9.]/g, ''); // Remove tudo que não for número ou ponto
            $(this).val(value + ' cm'); // Adiciona 'cm' no final
        }).on('focus', function () {
            let value = $(this).val().replace(' cm', ''); // Remove 'cm' ao focar
            $(this).val(value);
        }).on('blur', function () {
            let value = $(this).val().replace(/[^0-9.]/g, ''); // Remove caracteres inválidos ao perder o foco
            if (value) {
                $(this).val(value + ' cm'); // Adiciona 'cm' novamente
            }
        });

        $('.inputReais').on('input', function () {
            let value = $(this).val().replace(/[^0-9,]/g, ''); // Remove tudo que não for número ou vírgula
            $(this).val(value); // Mantém apenas os números e vírgula
        }).on('focus', function () {
            let value = $(this).val().replace('R$ ', '').replace('.', ','); // Remove 'R$ ' e ajusta o formato
            $(this).val(value);
        }).on('blur', function () {
            let value = $(this).val().replace(/[^0-9,]/g, ''); // Remove caracteres inválidos
            if (value) {
                $(this).val('R$ ' + value.replace(',', '.')); // Adiciona 'R$ ' e formata para número decimal
            }
        });

        $('.inputReaisFormatado').on('input', function () {
            let value = $(this).val().replace(/[^0-9,]/g, ''); // Remove tudo que não for número ou vírgula
            $(this).val(value); // Mantém apenas os números e vírgula
        }).on('focus', function () {

            $(this).val(value);
        }).on('blur', function () {
            let value = $(this).val().replace(/[^0-9,]/g, ''); // Remove caracteres inválidos
            if (value) {
                $(this).val('R$ ' + formatNumber(value)); // Adiciona 'R$ ' e formata para número decimal
            }
        });

        $('.inputPercent').on('input', function () {
            let value = $(this).val().replace(/[^0-9.]/g, ''); // Remove tudo que não for número ou ponto
            $(this).val(value + '%'); // Adiciona '%' no final
        }).on('focus', function () {
            let value = $(this).val().replace('%', ''); // Remove '%' ao focar
            $(this).val(value);
        }).on('blur', function () {
            let value = $(this).val().replace(/[^0-9.]/g, ''); // Remove caracteres inválidos ao perder o foco
            if (value) {
                $(this).val(value + '%'); // Adiciona '%' novamente
            }
        });

        $('#criar_intervalo_valores').click(function () {
            var valor_inicial = $('#valor_inicial').val();
            var valor_final = $('#valor_final').val();
            $.ajax({
                type: "POST",
                url: base_url + '/Integracao/cadastrarIntervaloValores',
                data: {
                    'valor_inicial': valor_inicial.replace(",", "."),
                    'valor_final': valor_final.replace(",", ".")
                },
                dataType: 'json',
                success: function (data) {
                    $('#valor_inicial').val('');
                    $('#valor_final').val('');
                    if (data.msg == "sucesso") {
                        Swal.fire({
                            title: 'Sucesso',
                            text: "Intervalo de valor cadastrado com sucesso",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.close();
                                $("#modalIntervaloValores").modal('hide');
                                $(".campo_intervalo_valores").html('');
                                $(".campo_intervalo_valores").append('<option value="">Selecione um intervalo de valores</option>');
                                for(var i = 0; i < data.intervalos_valores.length; i++){
                                    $(".campo_intervalo_valores").append('<option value="'+data.intervalos_valores[i].id_mercado_livre_intervalo_valores+'"> (R$ '+data.intervalos_valores[i].valor_inicial.replace(".", ",")+' - R$ '+data.intervalos_valores[i].valor_final.replace(".", ",")+')</option>');
                                }
                                //location.reload();
                            }
                        })
                    } else {
                        Swal.fire({
                            title: 'Erro',
                            text: data.erro,
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#bf0f0f',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.close();
                                $("#modalIntervaloValores").modal('hide');
                                //location.reload();
                            }
                        })
                    }

                }
            });
        });

        $('#criar_tabela_frete, #editar_tabela_frete').click(function () {
            var descricao = $('#descricao').val();
            var intervalos_fretes = $('#intervalos_fretes').val();
            var id_bling_tabela_frete = $('#id_bling_tabela_frete').val();
            if(id_bling_tabela_frete != ""){
                descricao = $('#descricao_edicao').val();
                intervalos_fretes = $('#intervalos_fretes_edicao').val();
            }
            $.ajax({
                type: "POST",
                url: base_url + '/Integracao/cadastrarTabelaFrete',
                data: {
                    'descricao': descricao,
                    'intervalos_fretes': intervalos_fretes,
                    'id_bling_tabela_frete': id_bling_tabela_frete
                },
                dataType: 'json',
                success: function (data) {
                    if (data.msg == "sucesso") {
                        Swal.fire({
                            title: 'Sucesso',
                            text: "Tabela de frete cadastrada com sucesso",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.close();
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
                                Swal.close();
                                location.reload();
                            }
                        })
                    }

                }
            });
            
        });

        $(".editar_tabela_frete").on("click", function () {
            var tabelaId = $(this).attr('id');
            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/getTabelaFrete',
                data: {
                    "tabelaId": tabelaId
                },
                dataType: 'json',
                success: function (data) {
                    $("#id_bling_tabela_frete").val(data.id_bling_tabela_frete);
                    $("#descricao_edicao").val(data.descricao);
                    $("#intervalos_fretes_edicao").val(data.fk_id_intervalo_valores);
                    $("#modalEditarTabelaFrete").modal('show');
                }
            });
        });

        $(".excluir_tabela_frete").on("click", function () {
            var tabelaId = $(this).attr('id');

            Swal.fire({
                title: "Tem certeza que deseja excluir?",
                text: "Isso removera todas informações da tabela de frete",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sim, Apagar!",
                cancelButtonText: "Não, Cancelar!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'Integracao/excluirTabelaFrete',
                        data: {
                            "tabelaId": tabelaId
                        },
                        async: false,
                        dataType: 'json',
                        success: function (data) {
                            if (data.resposta == "sucesso") {
                                Swal.fire({
                                    title: 'Sucesso',
                                    text: "Tabela de frete excluída com sucesso",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        Swal.close();
                                        location.reload();
                                    }
                                });
                            }
                        }
                    });
                }
            });



        });

        $(".excluir_frete").on("click", function () {
            var id_bling_tabela_frete_intervalos = $(this).attr('id');

            Swal.fire({
                title: "Tem certeza que deseja excluir?",
                text: "Isso removera todas informações da tabela de frete",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sim, Apagar!",
                cancelButtonText: "Não, Cancelar!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'Integracao/excluirFrete',
                        data: {
                            "id_bling_tabela_frete_intervalos": id_bling_tabela_frete_intervalos
                        },
                        async: false,
                        dataType: 'json',
                        success: function (data) {
                            if (data.msg == "sucesso") {
                                Swal.fire({
                                    title: 'Sucesso',
                                    text: "Intervalo de frete excluído com sucesso",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        var fretes = data.fretes_cadastrados;
                                        $('.div_fretes_cadastrados_ml_'+data.id_bling_tabela_frete).html('');
                                        for(var i = 0; i < fretes.length; i++){
                                            var string_frete = '<div class="col-md-12"  style="display: flex; flex-direction: row;" ><h4 style="margin-right: 10px" >De '+fretes[i].de+'Kg Até '+fretes[i].ate+'Kg -> R$ '+fretes[i].valor+'</h4> <a href="/Integracao/excluirFrete/'+fretes[i].id_bling_tabela_frete_intervalos    +'"><i style="color: #b30000; font-size: 20px!important" class="fa fa-trash"></i></a></div>'
                                            $('.div_fretes_cadastrados_ml_'+data.id_bling_tabela_frete).append(string_frete);
                                        }
                                        Swal.close();
                                    }
                                });
                            }
                        }
                    });
                }
            });
        });


        $('#atualizar_produto_bling').click(function () {

            var produtoId = $("#produtoId").val();
            const selectedValue = $('input[name="inlineRadioOptions"]:checked').val();
            if (selectedValue == "total_custo") {
                var valor_atualziar = $("#custo_total").html();
            } else {
                var valor_atualziar = $("#custo_produto").val();
            }


            $.ajax({
                type: "POST",
                url: base_url + '/Integracao/atualizaProdutoBling',
                data: {
                    'produtoId': produtoId,
                    'valor_atualziar': valor_atualziar.replace("R$ ", "")
                },
                dataType: 'json',
                success: function (data) {
                    if (data == "sucesso") {
                        Swal.fire({
                            title: 'Sucesso',
                            text: "Produto atualizado com sucesso",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.close()
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
                                Swal.close()
                            }
                        })
                    }



                }
            });
        });

        $('#atualizar_lojas_bling').click(function () {

            var produtoId = $("#produtoId").val();
            var valor_atualziar_ml = $("#valor_venda_203656717").val().replace("R$ ", "");
            var valor_campanha_ml = $("#valor_campanha_203656717").val().replace("R$ ", "");
            var valor_atualziar_sp = $("#valor_venda_203718490").val().replace("R$ ", "");
            var valor_campanha_sp = $("#valor_campanha_203718490").val().replace("R$ ", "");
            var valor_atualziar_mg = $("#valor_venda_203780244").val().replace("R$ ", "");
            var valor_campanha_mg = $("#valor_campanha_203780244").val().replace("R$ ", "");
            var valor_atualziar_ym = $("#valor_venda_204916439").val().replace("R$ ", "");
            var valor_campanha_ym = $("#valor_campanha_204916439").val().replace("R$ ", "");

            let mensagensErro = []; // Array para acumular erros
            let mensagensSucesso = []; // Array para acumular erros

            Swal.fire({
                title: '',
                html: '<strong> <h2> Estamos atualizando as informações!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                didOpen: () => { Swal.showLoading(); },
                icon: "warning",
            })



            //Atualiza ML
            $.ajax({
                type: "POST",
                url: base_url + '/Integracao/atualizaProdutoMkt',
                data: {
                    'produtoId': produtoId,
                    'valor_atualziar': valor_atualziar_ml,
                    'valor_campanha': valor_campanha_ml,
                    'lojaId': 203656717
                },
                dataType: 'json',
                success: function (data) {
                    if (data == "sucesso") {
                        mensagensSucesso.push("Mercado Livre atualizado com sucesso!");
                    } else {
                        mensagensErro.push("Ocorreram erros ao atualizar os produtos do Mercado Livre, verifique se está configurado corretamente!");
                    }



                }
            });

            setTimeout(() => {
                //Atualiza SP
                $.ajax({
                    type: "POST",
                    url: base_url + '/Integracao/atualizaProdutoMkt',
                    data: {
                        'produtoId': produtoId,
                        'valor_atualziar': valor_atualziar_sp,
                        'valor_campanha': valor_campanha_sp,
                        'lojaId': 203718490
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data == "sucesso") {
                            mensagensSucesso.push("Shopee atualizado com sucesso!");
                        } else {
                            mensagensErro.push("Ocorreram erros ao atualizar os produtos da Shopee, verifique se está configurado corretamente!");
                        }



                    }
                });
            }, 2500);


            setTimeout(() => {
                //Atualiza MG
                $.ajax({
                    type: "POST",
                    url: base_url + '/Integracao/atualizaProdutoMkt',
                    data: {
                        'produtoId': produtoId,
                        'valor_atualziar': valor_atualziar_mg,
                        'valor_campanha': valor_campanha_mg,
                        'lojaId': 203780244
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data == "sucesso") {
                            mensagensSucesso.push("Magalu atualizado com sucesso!");
                        } else {
                            mensagensErro.push("Ocorreram erros ao atualizar os produtos do Magalu, verifique se está configurado corretamente!");
                        }



                    }
                });
            }, 5000);


            setTimeout(() => {
                //Atualiza YM
                $.ajax({
                    type: "POST",
                    url: base_url + '/Integracao/atualizaProdutoMkt',
                    data: {
                        'produtoId': produtoId,
                        'valor_atualziar': valor_atualziar_ym,
                        'valor_campanha': valor_campanha_ym,
                        'lojaId': 204916439
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data == "sucesso") {
                            mensagensSucesso.push("Yampi atualizado com sucesso!");
                        } else {
                            mensagensErro.push("Ocorreram erros ao atualizar os produtos da Yampi, verifique se está configurado corretamente! E tente novamente.");
                        }



                    }
                });
            }, 7500);


            // Após todas as operações
            setTimeout(() => {
                if (mensagensErro.length > 0 && mensagensSucesso.length > 0) {

                    Swal.fire({
                        title: 'Atenção',
                        html: " <strong> Erros:</strong><br>" + mensagensErro.join("<br><br>") + "<br><br><strong> Sucesso:</strong><br>" + mensagensSucesso.join("<br>"), // Junta as mensagens com quebras de linha
                        icon: 'warning',
                        confirmButtonColor: '#bf0f0f',
                        confirmButtonText: 'OK!'
                    });

                } else if (mensagensErro.length > 0) {

                    Swal.fire({
                        title: 'Erros encontrados',
                        html: mensagensErro.join("<br><br>"), // Junta as mensagens com quebras de linha
                        icon: 'error',
                        confirmButtonColor: '#bf0f0f',
                        confirmButtonText: 'OK!'
                    });
                } else if (mensagensSucesso.length > 0) {

                    Swal.fire({
                        title: 'Sucesso ao realizar a ação!',
                        html: mensagensSucesso.join("<br><br>"), // Junta as mensagens com quebras de linha
                        icon: 'error',
                        confirmButtonColor: '#bf0f0f',
                        confirmButtonText: 'OK!'
                    });
                }

            }, 10000); // Timeout para aguardar operações assíncronas finalizarem
        });

        function obterMes(numero) {
            const meses = ["jan", "fev", "mar", "abr", "mai", "jun", "jul", "ago", "set", "out", "nov", "dez"];

            if (numero < 1 || numero > 12) {
                return "Número inválido! Digite um valor entre 1 e 12.";
            }
            return meses[numero - 1]; // Subtraímos 1 porque os arrays começam em 0
        }

        function obterNumero(mes) {
            const meses = ["jan", "fev", "mar", "abr", "mai", "jun", "jul", "ago", "set", "out", "nov", "dez"];

            const indice = meses.indexOf(mes.toLowerCase());
            if (indice === -1) {
                return "Mês inválido! Digite um valor válido como 'jan', 'fev', etc.";
            }
            return indice + 1; // Adicionamos 1 porque os meses são baseados em 1
        }

        function formatNumber(input) {
            if (typeof input === "string") {
                // Replace comma with dot for consistent processing
                input = input.replace(',', '.');
            }

            // Convert input to float to ensure proper handling
            const numericValue = parseFloat(input);

            if (isNaN(numericValue)) {
                throw new Error("Invalid input, please provide a valid number or string representation of a number.");
            }

            // Format the number to the desired currency format
            return numericValue.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function parseFormattedNumber(value) {
            // Validar se é uma string
            if (typeof value !== "string") {
                throw new Error("Input must be a formatted string");
            }

            // Substituir os separadores e converter para número
            const normalizedValue = value.replace(/\./g, "").replace(",", ".");
            const number = parseFloat(normalizedValue);

            if (isNaN(number)) {
                throw new Error("Input string is not a valid formatted number");
            }

            return number;
        }

        $('.nav-tabs .nav-link.nav-dre').on('click', function (event) {
            loadingDeTela()
            // Obtém o href da aba clicada (o ID do conteúdo associado)
            const $containerVariadas = $(".despesas-container");
            $containerVariadas.empty();
            const $containerFixa = $(".despesas-fixa");
            $containerFixa.empty();
            const $containerImpostos = $(".impostos_div");
            $containerImpostos.empty();

            var tabId = $(this).attr('id');

            var mes_ref = obterMes(tabId)
            $("#mes").val(mes_ref)

            var ano = $("#filtro_ano").val();
            var situacoes_filtro = $("#situacoes_filtro").val();

            if (ano != "") {

                var token_bling = $("#token_bling").val()

                $(`#total_pedidos_${mes_ref}`).text(``);
                $(`#custo_total_${mes_ref}`).text(`R$ 0`);
                $(`#desconto_loja_${mes_ref}`).text(`R$ 0`);
                $(`#valor_venda_${mes_ref}`).text(`R$ 0`);
                $(`#ticket_medio_${mes_ref}`).text(`R$ 0`);
                $(`#taxa_marketplace_${mes_ref}`).text(`R$ 0`);
                $(`#frete_vendedor_${mes_ref}`).text(`R$ 0`);
                $(`#ads_${mes_ref}`).text(`R$ 0`);
                $(`#taxa_fixa_${mes_ref}`).text(`R$ 0`);
                $(`#lucro_bruto_${mes_ref}`).text(`R$ 0`);
                $(`#percentual_custo_frete_${mes_ref}`).text(`0`);
                $(`#percentual_lucro_bruto_${mes_ref}`).text(`0`);
                $(`#percentual_custo_${mes_ref}`).text(`0`);

                $.ajax({
                    type: "POST",
                    url: base_url + '/Integracao/getDadosMes',
                    data: {
                        'ano': ano,
                        'mes': tabId,
                        'situacoes_filtro': situacoes_filtro
                    },
                    dataType: 'json',
                    success: async function (data) {

                        var taxa_fixa_ml = 0;
                        $.ajax({
                            type: "POST",
                            url: base_url + '/Integracao/getDadosML',
                            data: {
                            },
                            dataType: 'json',
                            success: function (data) {

                                taxa_fixa_ml = parseFloat(data.taxa_fixa_ml);

                            }
                        });

                        var taxa_fixa_ml_classico = 0;
                        $.ajax({
                            type: "POST",
                            url: base_url + '/Integracao/getDadosMLClassico',
                            data: {
                            },
                            dataType: 'json',
                            success: function (data) {

                                taxa_fixa_ml_classico = parseFloat(data.taxa_fixa_ml);


                            }
                        });

                        var taxa_fixa_ml_full = 0;
                        $.ajax({
                            type: "POST",
                            url: base_url + '/Integracao/getDadosMLFull',
                            data: {
                            },
                            dataType: 'json',
                            success: function (data) {

                                taxa_fixa_ml_full = parseFloat(data.taxa_fixa_ml);


                            }
                        });

                        var taxa_fixa_sp = 0;
                        $.ajax({
                            type: "POST",
                            url: base_url + '/Integracao/getDadosSP',
                            data: {
                            },
                            dataType: 'json',
                            success: function (data) {
                                taxa_fixa_sp = parseFloat(data.taxa_fixa_sp);

                            }
                        });

                        var taxa_fixa_mg = 0;
                        $.ajax({
                            type: "POST",
                            url: base_url + '/Integracao/getDadosMG',
                            data: {
                            },
                            dataType: 'json',
                            success: function (data) {

                                taxa_fixa_mg = parseFloat(data.taxa_fixa_mg);



                            }
                        });

                        var taxa_fixa_ym = 0;
                        $.ajax({
                            type: "POST",
                            url: base_url + '/Integracao/getDadosYM',
                            data: {
                            },
                            dataType: 'json',
                            success: function (data) {
                                taxa_fixa_ym = parseFloat(data.taxa_fixa_ym);

                            }
                        });



                        setTimeout(() => {

                            Object.keys(data).forEach(key => {

                                var indice = key;
                                const valorVenda = data[key.replace("_taxa_fixa", "")]?.valor_venda || 0;

                                // Verifica se a chave é uma chave "normal" ou uma chave "_taxa_fixa"
                                if (key.endsWith("_taxa_fixa")) {
                                    const qtdVendasTaxaFixa = data[key]?.qtd_Vendas || 0;

                                    var valor_taxa_fixa = 0;
                                    var valor_taxa_fixa_perc = 0;
                                    if (key.replace("_taxa_fixa", "") == 203656717) {
                                        valor_taxa_fixa = qtdVendasTaxaFixa * taxa_fixa_ml;
                                        if (valorVenda != 0) {
                                            valor_taxa_fixa_perc = (parseFloat(valor_taxa_fixa) / parseFloat(valorVenda)) * 100;
                                        }
                                    } else if (key.replace("_taxa_fixa", "") == 203718490) {
                                        valor_taxa_fixa = qtdVendasTaxaFixa * taxa_fixa_sp;
                                        if (valorVenda != 0) {
                                            valor_taxa_fixa_perc = (parseFloat(valor_taxa_fixa) / parseFloat(valorVenda)) * 100;
                                        }
                                    } else if (key.replace("_taxa_fixa", "") == 203780244) {
                                        valor_taxa_fixa = qtdVendasTaxaFixa * taxa_fixa_mg;
                                        if (valorVenda != 0) {
                                            valor_taxa_fixa_perc = (parseFloat(valor_taxa_fixa) / parseFloat(valorVenda)) * 100;
                                        }
                                    } else if (key.replace("_taxa_fixa", "") == 204074891) {
                                        valor_taxa_fixa = qtdVendasTaxaFixa * taxa_fixa_ml_full;
                                        if (valorVenda != 0) {
                                            valor_taxa_fixa_perc = (parseFloat(valor_taxa_fixa) / parseFloat(valorVenda)) * 100;
                                        }
                                    } else if (key.replace("_taxa_fixa", "") == 204916439) {
                                        valor_taxa_fixa = qtdVendasTaxaFixa * taxa_fixa_ym;
                                        if (valorVenda != 0) {
                                            valor_taxa_fixa_perc = (parseFloat(valor_taxa_fixa) / parseFloat(valorVenda)) * 100;
                                        }
                                    } else if (key.replace("_taxa_fixa", "") == 204950867) {
                                        valor_taxa_fixa = qtdVendasTaxaFixa * taxa_fixa_ml_classico;
                                        if (valorVenda != 0) {
                                            valor_taxa_fixa_perc = (parseFloat(valor_taxa_fixa) / parseFloat(valorVenda)) * 100;
                                        }
                                    }

                                    // Atualiza os elementos no DOM com valores de taxa fixa
                                    $("#taxa_fixa_" + key.replace("_taxa_fixa", "") + "_" + mes_ref).html("R$ " + valor_taxa_fixa);
                                    $("#perc_taxa_fixa_" + key.replace("_taxa_fixa", "") + "_" + mes_ref).html(valor_taxa_fixa_perc.toFixed(2) + "%");
                                } if (key.endsWith("_custo")) {

                                    const custo_produto = data[key] || 0;

                                    $("#custo_produto_" + key.replace("_custo", "") + "_" + mes_ref).html(custo_produto ? "R$ " + custo_produto.toFixed(2) : "R$ 0");
                                } if (key.endsWith("_ads")) {

                                    var valor_ads = data[key] || 0;

                                    valor_ads = parseFloat(valor_ads);
                                    if (valor_ads != 0) {

                                        $("#valor_ads_" + key.replace("_ads", "") + "_div_" + mes_ref).html(valor_ads ? "R$ " + valor_ads.toFixed(2) : "R$ 0");
                                        $("#valor_ads_" + key.replace("_ads", "") + "_" + mes_ref).html(valor_ads ? "R$ " + valor_ads.toFixed(2) : "R$ 0");
                                    } else {
                                        $("#valor_ads_" + key.replace("_ads", "") + "_div_" + mes_ref).html("R$ 0");
                                        $("#perc_ads_" + key.replace("_ads", "") + "_" + mes_ref).html("0");
                                    }



                                } else {
                                    // Verifica se os valores são válidos antes de usá-los
                                    const qtdVendas = data[key]?.qtd_Vendas || 0;
                                    const totalDesconto = data[key]?.total_desconto || 0;
                                    const ticketMedio = data[key]?.ticket_medio || 0;
                                    const totalTaxaMkt = data[key]?.total_taxa_mkt || 0;
                                    const totalTaxaMktPercentual = data[key]?.total_taxa_mkt_percentual || 0;
                                    const totalFrete = data[key]?.total_frete || 0;
                                    const totalFretePercentual = data[key]?.total_frete_percentual || 0;


                                    // Atualiza os elementos no DOM com valores verificados
                                    $("#qtd_pedidos_" + key + "_" + mes_ref).html(qtdVendas);
                                    $("#desconto_loja_" + key + "_" + mes_ref).html(totalDesconto ? "R$ " + totalDesconto : "R$ 0");
                                    $("#valor_venda_" + key + "_" + mes_ref).html(valorVenda ? "R$ " + valorVenda : "R$ 0");

                                    $("#tickt_medio_" + key + "_" + mes_ref).html(ticketMedio ? "R$ " + ticketMedio : "R$ 0");

                                    $("#total_mkt_" + key + "_" + mes_ref).html(totalTaxaMkt ? "R$ " + totalTaxaMkt : "R$ 0");
                                    $("#perc_custo_" + key + "_" + mes_ref).html(totalTaxaMktPercentual ? +totalTaxaMktPercentual + "%" : "0");

                                    $("#frete_vendedor_" + key + "_" + mes_ref).html(totalFrete ? "R$ " + totalFrete : "R$ 0");
                                    $("#perc_custo_frete_" + key + "_" + mes_ref).html(totalFretePercentual ? +totalFretePercentual + "%" : "0");

                                    calculaValorLucro(key, mes_ref)
                                }
                            });



                        }, 5000);





                    }
                });

                setTimeout(() => {

                    // Certifique-se de que o mês seja um número entre 1 e 12
                    if (tabId < 1 || tabId > 12) {
                        throw new Error("O mês deve estar entre 1 e 12.");
                    }

                    // Adiciona o zero à esquerda para o formato "MM"
                    const paddedMonth = tabId.toString().padStart(2, '0');

                    // Cria as datas do primeiro e último dia
                    const firstDay = `${ano}-${paddedMonth}-01`;

                    // Obtém o último dia do mês
                    const lastDay = new Date(ano, tabId, 0); // A função new Date com dia 0 retorna o último dia do mês anterior
                    const formattedLastDay = `${ano}-${paddedMonth}-${lastDay.getDate().toString().padStart(2, '0')}`;








                    carregarContasBling(firstDay, formattedLastDay, ano, tabId, situacoes_filtro, mes_ref);
                }, 6000);









            }

        });

        let ignoreFocus = false;

        // Foca no elemento #codigo_sku ao carregar
        $("#codigo_sku").focus();


        $("#codigo_sku").blur(function () {
            if (!ignoreFocus) {
                $("#codigo_sku").focus();
            }
        });

        // Desabilita o foco ao clicar no botão do menu
        $(".sidebar-toggle").click(function () {
            ignoreFocus = true;
            $("#codigo_sku").blur(); // Remove o foco do input
            setTimeout(() => {
                ignoreFocus = false; // Habilita novamente após um tempo, se necessário
            }, 500);
        });

        // Usando event delegation para o contêiner que não muda
        $("#divDados").on("mousedown", function () {
            ignoreFocus = true;
        });

        $("#divDados").on("mouseup", function (event) {
            setTimeout(function () {
                ignoreFocus = false;
                let scrollPos = $(window).scrollTop();

                // Se o elemento clicado não for o #campo_pesquisa, foca no #codigo_sku
                if (event.target.id !== "campo_pesquisa") {
                    $("#codigo_sku").focus();
                }

                $(window).scrollTop(scrollPos);
            }, 100);
        });

        $("#divCampos").on("mousedown", function (event) {
            // Se o clique for no campo observacoes, não altera o foco
            if (event.target.id === "observacoes") {
                ignoreFocus = true;
            }
        });

        $("#divCampos").on("mouseup", function (event) {
            setTimeout(function () {
                let scrollPos = $(window).scrollTop();

                // Se o elemento clicado não for o #observacoes, foca no #codigo_sku
                if (event.target.id !== "observacoes") {
                    $("#codigo_sku").focus();
                }

                $(window).scrollTop(scrollPos);
                ignoreFocus = false;
            }, 100);
        });


        $("#campo_pesquisa").on("blur", function () {
            $("#codigo_sku").focus();
        })

        $("#codigo_sku").on('input', function () {
            window.scrollTo(0, 0);
        });

        $(document).ready(function () {
            if ($("#codigo_sku").length) {
                $("#codigo_sku").on("keypress", function (e) {
                    if (e.key === 'Enter') {
                        var codigo = $("#codigo_sku").val();
                        var tipo = $("#tipo_conferencia").val();
                        var deposito = $("#deposito").val();
                        var arquivo = $("#arquivo_selecionado").val();

                        var urlDados = 'Integracao/getProdutoMercadoria';
                        if (tipo != undefined && tipo == "P") {
                            urlDados = 'Integracao/getProdutoMercadoriaConferencia';
                        } else if (tipo != undefined && tipo == "full") {
                            urlDados = 'MercadoLivreIntegracao/getProdutoMercadoriaFull';
                        }

                        if (codigo !== "") {
                            $.ajax({
                                type: "POST",
                                url: base_url + urlDados,
                                data: {
                                    "codigo": codigo,
                                    "deposito": deposito,
                                    "arquivo": arquivo
                                },
                                async: false,
                                dataType: 'json',
                                success: function (data) {
                                    if (data && data.length > 0) {
                                        $("#dadosConferencia").empty();
                                        data.forEach(function (produto, index) {
                                            let backgroundColor = (index % 2 === 0) ? "#ffffff" : "#f7f7f7";

                                            //aqui eu posso receber 3 tipos de id difetentes, ID_TMP_PRODUTO_FULL, ID_TMP_PRODUTO_BLING e ID_CONFERENCIA_PRODUTOS_BLING, e vou ter labels diferenes dependendo de cada,

                                            if(produto.ID_TMP_PRODUTO_FULL != null){
                                                var id_produto_iterado = produto.ID_TMP_PRODUTO_FULL
                                                var label_esperado = "Esperados Full:"
                                            }else if(produto.ID_TMP_PRODUTO_BLING != null){
                                                var id_produto_iterado = produto.ID_TMP_PRODUTO_BLING
                                                var label_esperado = "Esperados NF:"
                                            }else if(produto.ID_CONFERENCIA_PRODUTOS_BLING != null){
                                                var id_produto_iterado = produto.ID_CONFERENCIA_PRODUTOS_BLING
                                                var label_esperado = "Estoque Bling:"
                                            }

                                            let produtoHtml = `
                                            <div class="row div_principal" style="background-color: ${backgroundColor}; border-radius: 2px; padding: 5px">
                                                <div class="col-md-3" style="display: flex; flex-direction: column; justify-content: space-evenly; align-items: flex-start;">
                                                    <div style="display: flex; justify-content: flex-start; align-items: center;">
                                                        <span class="descricao_completa_produto" style="font-weight: bold!important; font-size: 12px; margin-left: 5px">${produto.DESCRICAO_PRODUTO}</span>
                                                    </div>
                                                    <div style="display: flex; flex-direction: row">
                                                        <div id="foto">
                                                            <img style="width: 80px; margin-bottom: 15px" src="${produto.IMG_PRODUTO || '/template/images/caixa_padrao.png'}" alt="">
                                                        </div>
                                                        <div id="dados" style="display: flex; flex-direction: column; justify-content: center; margin-left: 15px;">
                                                            <h5><span style="font-weight: bold!important; font-size: 14px;">SKU: </span><span class="sku_produto">${produto.CODIGO_PRODUTO}</span></h5>
                                                            <h5><span style="font-weight: bold!important; font-size: 14px;">Cod. Fornecedor: </span>${produto.CODIGO_FABRICANTE}</h5>
                                                            <h5><span style="font-weight: bold!important; font-size: 14px;">Localização: </span><span style="color: #c8204a;">${produto.LOCALIZACAO}</span></h5>
                                                            ${produto.CODIGO_FULL !== undefined ? `<h5><span style="font-weight: bold!important; font-size: 14px;">Código Full: </span> ${produto.CODIGO_FULL}</h5>` : ''}
                                                        </div>
                                                    </div>
                                                    <div style="display: flex; justify-content: center; align-items: center;">
                                                        <h5 style="color: #c8204a; font-weight: bold;"><img style="height: 20px; margin-left: 15px; margin-bottom: 5px" src="/template/images/Tag.png" alt=""> Observações: </h5>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row divTituloConferencia" style="margin-bottom: 10px" data-codigo="${produto.CODIGO_PRODUTO}" data-codigofull="${produto.CODIGO_FULL}">
                                                        <div style="border: 2px solid #91c9d0; border-radius: 8px; display: flex; justify-content: center; padding-bottom: 5px; padding-top: 5px" class="col-md-12">
                                                            <span style="font-size: 17px; font-weight: bold">Conferência de produtos <img style="height: 40px;" src="/template/images/barcode-scaner.png" alt=""> </span>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-bottom: 10px">
                                                        <div class="col-md-4" style="display: flex; justify-content: center; align-items: center;">
                                                            <div style="text-align: center;border: 2px solid #9629aa; background-color: #9629aa; color: #FFFFFF; border-radius: 8px; padding: 5px 0; width: 190px;">
                                                                <span id="span_esperado_${id_produto_iterado}" style="font-size: 14px; font-weight: 500">${label_esperado} ${produto.QTD_NF}</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4" style="display: flex; justify-content: center; align-items: center;">
                                                            <div style="text-align: center;border: 2px solid #92a9c3; background-color: #92a9c3; color: #FFFFFF; border-radius: 8px; padding: 5px 0; width: 190px;">
                                                                <span id="span_lido_${id_produto_iterado}" style="font-size: 15px; font-weight: 500">Lidos: ${produto.LIDOS}</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4" style="display: flex; justify-content: center; align-items: center;">
                                                            <div id="div_restante_${id_produto_iterado}" style="text-align: center;border: 2px solid #686ee4; background-color: #686ee4; color: #FFFFFF; border-radius: 8px; padding: 5px 0; width: 190px;">
                                                                <span id="span_restante_${id_produto_iterado}" style="font-size: 14px; font-weight: 500">Produtos restantes: ${produto.QTD_NF != 0 ? produto.QTD_NF - produto.LIDOS : "-"}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 dados_financeiro" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <img style="height: 35px;" id="${id_produto_iterado}" class="aumentarQtd" src="/template/images/add.png" alt="">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <img style="height: 35px;" id="${id_produto_iterado}" class="removerQtd" src="/template/images/remove.png" alt="">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <img style="height: 35px;" id="${id_produto_iterado}" class="removerProduto" src="/template/images/trash.png" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            `;
                                            $("#dadosConferencia").append(produtoHtml);
                                            /*                                             if(produto.CODIGO_FULL == "HQNA00292"){
                                                                                            console.log("produto.QTD_NF")
                                                                                            console.log(produto.QTD_NF)
                                                                                            console.log("produto.LIDO")
                                                                                            console.log(produto.LIDOS)
                                                                                            
                                                                                        } */
                                            verificaProdutosRestante(produto.QTD_NF, produto.LIDOS ? produto.LIDOS : 0, id_produto_iterado)
                                        });

                                        $("#codigo_sku").val("").focus();

                                        setTimeout(() => {
                                            

                                            var produto_adicionado = data[0];

                                            if(produto_adicionado.ID_TMP_PRODUTO_FULL != null){
                                                var id_produto_iterado = produto_adicionado.ID_TMP_PRODUTO_FULL
                                                var tipoEstoque = "QTD FULLL"
                                            }else if(produto_adicionado.ID_TMP_PRODUTO_BLING != null){
                                                var id_produto_iterado = produto_adicionado.ID_TMP_PRODUTO_BLING
                                                var tipoEstoque = "ESPERADO NF"
                                            }else if(produto_adicionado.ID_CONFERENCIA_PRODUTOS_BLING != null){
                                                var id_produto_iterado = produto_adicionado.ID_CONFERENCIA_PRODUTOS_BLING
                                                var tipoEstoque = "ESTOQUE BLING"
                                            }


                                            $("#produtoTMP").val(id_produto_iterado)

                                            $("#dadosProdutoIndividual").empty();  // Limpa o contêiner antes de inserir novos dados
                                            var produto_adicionado = data[0];

                                            verificaProdutosRestante(produto_adicionado.QTD_NF, produto_adicionado.LIDOS, id_produto_iterado)

                                            // Cria a estrutura de HTML
                                            var html = '<div style="display: flex; flex-direction: row">';

                                            // Se houver uma imagem do produto, exibe-a; caso contrário, exibe uma imagem padrão
                                            html += '<div id="foto">';
                                            if (produto_adicionado.IMG_PRODUTO && produto_adicionado.IMG_PRODUTO !== null) {
                                                html += '<img style="width: 200px; margin-bottom: 15px" src="' + produto_adicionado.IMG_PRODUTO + '" alt="Produto">';
                                            } else {
                                                html += '<img style="width: 200px; margin-bottom: 15px" src="/template/images/caixa_padrao.png" alt="Produto padrão">';
                                            }
                                            html += '</div>';

                                            // Adiciona os dados do produto como SKU, Cod. Fornecedor e Localização
                                            html += '<div id="dados" style="display: flex; flex-direction: column; justify-content: space-evenly; margin-left: 15px;">';



                                            // SKU
                                            html += '<h5 style="font-size: 25px;">';
                                            html += '<span style="font-weight: bold!important;">SKU: </span>';
                                            html += '<span class="sku_produto">' + (produto_adicionado.CODIGO_PRODUTO || 'Sem dados') + '</span>';
                                            html += '</h5></br>';

                                            if (produto_adicionado.CODIGO_FULL != null) {
                                                // SKU
                                                html += '<h5 style="font-size: 25px;">';
                                                html += '<span style="font-weight: bold!important;">Código Full: </span>';
                                                html += '<span class=""> ' + (produto_adicionado.CODIGO_FULL || 'Sem dados') + '</span>';
                                                html += '</h5></br>';
                                            }

                                            // Código do Fabricante
                                            html += '<h5 style="font-size: 25px;">';
                                            html += '<span style="font-weight: bold!important;">Cod. Fornecedor: </br></br></span>';
                                            html += (produto_adicionado.CODIGO_FABRICANTE || 'Sem dados');
                                            html += '</h5></br>';

                                            // Localização
                                            html += '<h5 style="font-size: 40px;">';
                                            html += '<span style="font-weight: bold!important;">Localização: </br></br></span>';
                                            html += (produto_adicionado.LOCALIZACAO || 'Sem dados');
                                            html += '</h5></br>';

                                            html += '</div>';
                                            html += '</div></br></br>';

                                            html += '<div style="display: flex; flex-direction: row"> ';
                                            html += '<div id="foto">';
                                            html += '    <h5 style="font-size: 25px;" > <span style="font-weight: bold!important;">'+tipoEstoque+': </span>  <span class="sku_produto"> ' + produto_adicionado.QTD_NF + ' </span> </h5> </br>';
                                            html += '    <h5 style="font-size: 25px;" > <span style="font-weight: bold!important;">LIDOS: </span>  <span class="sku_produto"> ' + produto_adicionado.LIDOS + ' </span> </h5> </br>';
                                            html += '</div>';
                                            html += '</div>';

                                            // Insere o HTML gerado no contêiner #dadosProdutoIndividual
                                            $("#dadosProdutoIndividual").html(html);

                                        }, 1);
                                    }else{
                                        $("#codigo_sku").val("").focus();
                                        Swal.fire({
                                            title: 'Atenção',
                                            text: "Produto não encontrado",
                                            icon: 'warning',
                                            confirmButtonColor: '#3085d6',
                                        }).then((result) => {
                                            Swal.close()
                                        })
                                    }
                                }
                            });
                        }
                    }
                });
            } else {
                console.error("Elemento #codigo_sku não encontrado no DOM.");
            }
        });
        $(document).ready(function () {
            $("#copiar_codigo_full").on("click", async function () {
                var texto = $("#modalEtiquetasLabel").text();
                var codigo = texto.match(/:\s*(\S+)/)[1];

                if (navigator.clipboard && typeof navigator.clipboard.writeText === "function") {
                    try {
                        const permission = await navigator.permissions.query({ name: "clipboard-write" });

                        if (permission.state === "granted" || permission.state === "prompt") {
                            await navigator.clipboard.writeText(codigo);
                        } else {
                            console.warn("Permissão para acesso ao clipboard não concedida.");
                            fallbackCopyText(codigo);
                            $("#codigo_sku").focus();
                        }
                    } catch (err) {
                        console.error("Erro ao copiar usando clipboard API:", err);
                        fallbackCopyText(codigo);
                    }
                } else {
                    console.warn("A API Clipboard não está disponível neste navegador.");
                    fallbackCopyText(codigo);
                }
            });
        });


        function fallbackCopyText(text) {
            var tempInput = document.createElement("textarea");
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            tempInput.setSelectionRange(0, 99999); // Para dispositivos móveis
            document.body.removeChild(tempInput);

            navigator.clipboard.writeText(text).then(() => {
            }).catch((err) => {
                console.error("Erro ao copiar (fallback):", err);
            });
        }

        $("#etiqueta_full").on('click', function () {
            var codigo_full_principal = $(".codigo_full_principal").html();

            $("#modalEtiquetasLabel").html("");
            $("#modalEtiquetasLabel").html("Acessar etiqueta FULL: " + codigo_full_principal);
            $("#copiar_codigo_full").trigger("click")
            $("#copiar_codigo_full").css("display", "none");
            $("#modalEtiquetas").modal("show");
        })

        $(document).on('click', '.divTituloConferencia', function (e) {
            e.stopPropagation();

            let $this = $(this);

            // Evita múltiplos cliques rápidos
            if ($this.data("executando")) {
                return;
            }
            $this.data("executando", true);

            let tipo_conferencia = $("#tipo_conferencia").val();
            if (tipo_conferencia === "full") {
                let codigo = $this.data('codigo');
                let codigo_full = $this.data('codigofull');

                $.ajax({
                    type: "POST",
                    url: base_url + 'MercadoLivreIntegracao/getGtinCodigo',
                    data: { codigo: codigo },
                    dataType: 'json',
                    success: function (data) {
                        $("#gtin_atual").val(data);
                    },
                    complete: function () {
                        $this.data("executando", false);
                    }
                });

                if (codigo !== "") {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'MercadoLivreIntegracao/getCodigoFull',
                        data: { "codigo_full": codigo_full },
                        dataType: 'json',
                        success: function (data) {
                            if (data) {
                                let produto_adicionado = data;

                                $("#produtoTMP").val(produto_adicionado.ID_CONFERENCIA_PRODUTOS_BLING != null
                                    ? produto_adicionado.ID_CONFERENCIA_PRODUTOS_BLING
                                    : produto_adicionado.ID_TMP_PRODUTO_BLING
                                );

                                $("#dadosProdutoIndividual").empty();

                                verificaProdutosRestante(produto_adicionado.QTD_NF, produto_adicionado.LIDOS, produto_adicionado.ID_TMP_PRODUTO_BLING);

                                let html = `
                                    <div style="display: flex; flex-direction: row">
                                        <div id="foto">
                                            <img style="width: 200px; margin-bottom: 15px" 
                                            src="${produto_adicionado.IMG_PRODUTO || '/template/images/caixa_padrao.png'}" alt="Produto">
                                        </div>
                                        <div id="dados" style="display: flex; flex-direction: column; justify-content: space-evenly; margin-left: 15px;">
                                            <h5 style="font-size: 25px;">
                                                <span style="font-weight: bold!important;">SKU: </span>
                                                <span class="sku_produto">${produto_adicionado.CODIGO_PRODUTO || 'Sem dados'}</span>
                                            </h5></br>
                                            ${produto_adicionado.CODIGO_FULL ? `<h5 style="font-size: 25px;"><span style="font-weight: bold!important;">Código Full: </span><span class="codigo_full_principal" >${produto_adicionado.CODIGO_FULL}</span></h5></br>` : ''}
                                            <h5 style="font-size: 25px;">
                                                <span style="font-weight: bold!important;">Cod. Fornecedor: </br></br></span>
                                                ${produto_adicionado.CODIGO_FABRICANTE || 'Sem dados'}
                                            </h5></br>
                                            <h5 style="font-size: 40px;">
                                                <span style="font-weight: bold!important;">Localização: </br></br></span>
                                                ${produto_adicionado.LOCALIZACAO || 'Sem dados'}
                                            </h5></br>
                                        </div>
                                    </div></br></br>
                                    <div style="display: flex; flex-direction: row">
                                        <div id="foto">
                                            <h5 style="font-size: 25px;"><span style="font-weight: bold!important;">QTD FULL: </span><span class="sku_produto">${produto_adicionado.QTD_NF}</span></h5></br>
                                            <h5 style="font-size: 25px;"><span style="font-weight: bold!important;">LIDOS: </span><span class="sku_produto">${produto_adicionado.LIDOS}</span></h5></br>
                                        </div>
                                    </div>`;

                                $("#dadosProdutoIndividual").html(html);
                            }
                        },
                        complete: function () {
                            $this.data("executando", false);
                        }
                    });
                }

                // Evita loop infinito ao abrir o modal
                if (!$("#modalValidarGtin").hasClass("show")) {
                    $("#modalValidarGtin").modal("show");
                }
            }
        });

        $(".nova_tag").on("click", function (e) {
            var sku = $(this).attr('id');
            var orderid = $(this).data('orderid');
            var tipotag = $(this).data('tipotag');

            $(".div_tag_venda").hide();
            $(".div_tag_produto").hide();

            if(tipotag == "produto"){
                $(".div_tag_venda").hide();
                $(".div_tag_produto").show();
            }else{
                $(".div_tag_venda").show();
                $(".div_tag_produto").hide();
            }
            $("#tag_sku").val(sku);
            $("#tipotag").val(tipotag);
            $("#orderid").val(orderid);
            $("#modalNovaTag").modal("show");
        });

        $('.ocultar_produto').change(function () {
            let sku = $(this).data('sku');
            var ocultarProduto = $(this).is(':checked') ? 'S' : 'N';
            console.log("sku");
            console.log(sku);
            console.log("ocultarProduto");
            console.log(ocultarProduto);

            $.ajax({
                type: "POST",
                url: base_url + 'MercadoLivreIntegracao/OcultarProduto',
                data: {
                    "ocultarProduto": ocultarProduto,
                    "sku": sku
                },
                dataType: 'json',
                success: function (data) {

                },
                complete: function () {
                    $this.data("executando", false);
                }
            });
        });

        $("#id_tag_venda_editar").on("change", function (e) {
            var id_tag = $(this).val();

            $.ajax({
                type: "POST",
                url: base_url + 'MercadoLivreIntegracao/getTagID',
                data: {
                    "id": id_tag,
                    "tipo": "venda"
                },
                dataType: 'json',
                success: function (data) {
                    if(data != null){
                        $("#nova_tag_descricao_editar").val(data.descricao);
                        $("#cor_tag_editar").val(data.cor); 
                    }
                }
            });
        });

        $("#id_tag_sku_editar").on("change", function (e) {
            var id_tag = $(this).val();

            $.ajax({
                type: "POST",
                url: base_url + 'MercadoLivreIntegracao/getTagID',
                data: {
                    "id": id_tag,
                    "tipo": "produto"
                },
                dataType: 'json',
                success: function (data) {
                    if(data != null){
                        $("#nova_tag_descricao_editar").val(data.descricao);
                        $("#cor_tag_editar").val(data.cor); 
                    }
                }
            });
        });

        $("#salvar_tag").on("click", function (e) {
            console.log("salvar_tag")
            var id_tag_venda_editar = $("#id_tag_venda_editar").val();
            var id_tag_sku_editar = $("#id_tag_sku_editar").val();
            console.log("id_tag_venda_editar")
            console.log(id_tag_venda_editar)
            console.log("id_tag_sku_editar")
            console.log(id_tag_sku_editar)
            var sku = $("#tag_sku").val();
            var order_id = $("#orderid").val();
            var tipotag = $("#tipotag").val();
            var nova_tag_descricao = $("#nova_tag_descricao").val();
            var cor_tag = $("#cor_tag").val();

            if(id_tag_venda_editar != "" || id_tag_sku_editar != ""){
                if(id_tag_venda_editar != ""){
                    var id_tag = id_tag_venda_editar;
                }else{
                    var id_tag = id_tag_sku_editar;
                }
                
                var nova_tag_descricao_editar = $("#nova_tag_descricao_editar").val();
                var cor_tag_editar = $("#cor_tag_editar").val();

                $.ajax({
                    type: "POST",
                    url: base_url + 'MercadoLivreIntegracao/editarTag',
                    data: {
                        "id": id_tag,
                        "tipotag": tipotag,
                        "nova_tag_descricao": nova_tag_descricao_editar,
                        "cor_tag": cor_tag_editar,
                    },
                    dataType: 'json',
                    success: function (data) {
                        if(data != null){
                            location.reload();
                        }
                    }
                });
            }else{
                
                if(tipotag == "venda"){
                    var id_tag = $("#id_tag_venda").val();
                }else{
                    var id_tag = $("#id_tag_sku").val();
                }
                $.ajax({
                    type: "POST",
                    url: base_url + "MercadoLivreIntegracao/salvarTag",
                    data: {
                        "sku": sku,
                        "nova_tag_descricao": nova_tag_descricao,
                        "cor_tag": cor_tag,
                        "tipotag": tipotag,
                        "order_id": order_id,
                        "id_tag": id_tag
                    },
                    async: false,
                    dataType: 'json',
                    success: function (data) {
                        $("#nova_tag_descricao").val("");
                        $("#cor_tag").val("");
                    
                        if (data.tags.length > 0) {
                            $(".container_" + sku).html("");
                            $(".container_" + sku).html('<div class="badges-container" style="display: flex; gap: 5px;"></div>');
                            var tags_sku = [];
                    
                            for (let i = 0; i < data.tags.length; i++) {
                                const tag = data.tags[i];
                                tags_sku.push('<span id="' + tag.id_tag_sku + '-' + tag.sku + '-produto" style="background-color: ' + tag.cor + '; color: white; padding: 2px 5px; border-radius: 3px; margin-right: 5px;" class="badge badge_tag">' + tag.descricao + '</span>');
                            }
                    
                            $(".container_" + sku).find(".badges-container").html(tags_sku.join(" "));
                    
                            // Atualiza o select de produto
                            $("#id_tag_sku").find('option:not(:first)').remove();
                            for (let i = 0; i < data.tags_sku_atualizadas.length; i++) {
                                const tag = data.tags_sku_atualizadas[i];
                                $("#id_tag_sku").append(
                                    $('<option>', {
                                        value: tag.id_tag_sku,
                                        text: tag.descricao
                                    })
                                );
                            }
                            $("#id_tag_sku").trigger('change.select2');
                        }
                    
                        if (data.tags_venda.length > 0) {
                            $(".container_finan_" + order_id).html("");
                            $(".container_finan_" + order_id).html('<div class="badges-container" style="display: flex; gap: 5px;"></div>');
                            var tags_vendas = [];
                    
                            for (let i = 0; i < data.tags_venda.length; i++) {
                                const tag = data.tags_venda[i];
                                tags_vendas.push('<span id="' + tag.id_tag_venda + '-' + tag.order_id + '-venda" style="background-color: ' + tag.cor + '; color: white; padding: 2px 5px; border-radius: 3px; margin-right: 5px;" class="badge badge_tag">' + tag.descricao + '</span>');
                            }
                    
                            $(".container_finan_" + order_id).find(".badges-container").html(tags_vendas.join(" "));
                    
                            // Atualiza o select de venda
                            $("#id_tag_venda").find('option:not(:first)').remove();
                            for (let i = 0; i < data.tags_venda_atualizadas.length; i++) {
                                const tag = data.tags_venda_atualizadas[i];
                                $("#id_tag_venda").append(
                                    $('<option>', {
                                        value: tag.id_tag_venda,
                                        text: tag.descricao
                                    })
                                );
                            }
                            $("#id_tag_venda").trigger('change.select2');
                        }
                    
                        $("#modalNovaTag").modal("hide");
                    }
                    
                });
            }


            
        });


        $("#gerar_excel_full").on('click', function () {
            var dados_full = [];
            $('.div_principal').each(function () {
                var qtd_pedido = $(this).find('.input_qtd_pedido').val().trim();
                if (qtd_pedido !== "0" && qtd_pedido !== "") {
                    dados_full.push({
                        "qtd_pedido": qtd_pedido,
                        "id_produto_variacao": $(this).find('.id_produto_variacao').val()?.trim(),
                        "id_mlb": $(this).find('.id_mlb').val()?.trim()
                    });
                }
            });

            if (dados_full.length > 0) {
                var form = $('<form action="' + base_url + '/MercadoLivreIntegracao/gerarExcelFull" method="POST"></form>');
                form.append('<input type="hidden" name="dados_full" value=\'' + JSON.stringify(dados_full) + '\'>');
                $('body').append(form);
                form.submit();
                form.remove();
            } else {
                Swal.fire({
                    title: 'Atenção',
                    text: "Nenhuma quantidade determinada a enviar",
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
        });


        $("#criar_transferencia").click(function () {
            var dados_full = [];
            var x = 0;
            $('.div_principal').each(function () {
                if ($(this).find('.input_qtd_pedido').val() != "0" && $(this).find('.input_qtd_pedido').val() != "") {

                    dados_full[x] = {
                        "qtd_pedido": $(this).find('.input_qtd_pedido').val().trim(),
                        "id_produto_variacao": $(this).find('.id_produto_variacao').val()?.trim(),
                        "id_produto_bling": $(this).find('.id_produto_bling').val()?.trim(),
                        "id_mlb": $(this).find('.id_mlb').val()?.trim()
                    }
                }

                x++;
            });


            if (dados_full.length > 0) {

                var deposito_origem = $("#deposito_origem").val();
                var deposito_destino = $("#deposito_destino").val();
                var obs_transferencia = $("#obs_transferencia").val();

                $.ajax({
                    type: "POST",
                    url: base_url + '/MercadoLivreIntegracao/criarTransferenciaFull',
                    data: {
                        dados_full: dados_full,
                        deposito_destino: deposito_destino,
                        deposito_origem: deposito_origem,
                        obs_transferencia: obs_transferencia,
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data == "sucesso") {
                            Swal.fire({
                                title: 'Sucesso',
                                text: "Estoque atualizado com sucesso",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.close()
                                }
                            })
                        } else {
                            Swal.fire({
                                title: 'Erro',
                                text: data,
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

                    }
                });
            }

        })

        const codigosProdutoFull = JSON.parse($('#codigosProdutoFull').val() || '[]');
        const idsProdutoFull = JSON.parse($('#idsProdutoFull').val() || '[]');
        const codigosVendas = JSON.parse($('#codigosVendas').val() || '[]');

        // Percorrer o array
        $.each(codigosProdutoFull, function (index, codigo) {

            $.ajax({
                type: "POST",
                url: '/MercadoLivreIntegracao/getDataultimaVendaFull',
                data: {
                    'codigo': codigo
                },
                dataType: 'json',
                success: function (data) {
                    if (data != "") {
                        $(".span_ultima_venda" + index).html("")
                        $(".span_ultima_venda" + index).append("<span>" + formatarDataParaBR(data) + "</span>")
                    }
                }
            });

            $.ajax({
                type: "POST",
                url: '/MercadoLivreIntegracao/getTagsSku',
                data: {
                    'produtoId': codigo
                },
                dataType: 'json',
                success: function (data) {
                    if (data.length > 0) {
                        var tags_sku = [];
                        for (let i = 0; i < data.length; i++) {
                            const tag = data[i];
                            tags_sku.push('<span  id="' + tag.id_tag_sku + '-' + tag.sku + '-produto" style="background-color: ' + tag.cor + '; color: white; padding: 2px 5px; border-radius: 3px; margin-right: 5px;" class="badge badge_tag">' + tag.descricao + '</span>');
                        }

                        $(".container_" + codigo).find(".badges-container").html(tags_sku.join(" "));
                    }
                }
            });


        });

        $(".icone_atencao_kit").hide()

        $.each(idsProdutoFull, function (index, produtoId) {

            $.ajax({
                type: "POST",
                url: '/MercadoLivreIntegracao/getVerificaComposicao',
                data: {
                    'produtoId': produtoId
                },
                dataType: 'json',
                success: function (data) {
                    console.log(produtoId)
                    console.log(data)
                    if (data) {
                        $(".icone_exclamacao_" + produtoId).show()
                    }


                }
            });

        });

        function processarRequisicoes(codigosVendas) {
            let index = 0;
            const delay = 200; // Atraso de 200ms entre cada requisição

            function fazerRequisicao() {
                if (index >= codigosVendas.length) return;

                let order_id = codigosVendas[index];

                $.ajax({
                    type: "POST",
                    url: '/MercadoLivreIntegracao/getTagsVenda',
                    data: { 'order_id': order_id },
                    dataType: 'json',
                    success: function (data) {
                        if (data.length > 0) {
                            $(".container_finan_" + order_id).html("");
                            $(".container_finan_" + order_id).html('<div class="badges-container" style="display: flex; gap: 5px;"></div>');
                            var tags_vendas = data.map(tag =>
                                `<span id="${tag.id_tag_venda}-${tag.order_id}-venda" 
                                       style="background-color: ${tag.cor}; color: white; padding: 2px 5px; border-radius: 3px; margin-right: 5px;" 
                                       class="badge badge_tag">
                                    ${tag.descricao}
                                </span>`
                            );
                            $(".container_finan_" + order_id).find(".badges-container").html(tags_vendas.join(" "));
                        }
                    }
                });

                index++;
                setTimeout(fazerRequisicao, delay); // Espera antes de enviar a próxima requisição
            }

            fazerRequisicao();
        }

        // Chamar a função para iniciar as requisições com controle de taxa
        processarRequisicoes(codigosVendas);


        $(document).on("click", ".badge.badge_tag", function () {
            var id = $(this).attr('id');
            id = id.split("-")
            var sku = id[1]
            var tipo = id[2]
            id = id[0]



            Swal.fire({
                title: "Tem certeza que deseja excluir?",
                text: "Isso removera a tag",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sim, Apagar!",
                cancelButtonText: "Não, Cancelar!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: base_url + "MercadoLivreIntegracao/removerTag",
                        data: {
                            "id": id,
                            "sku": sku,
                            'tipotag': tipo
                        },
                        async: false,
                        dataType: 'json',
                        success: function (data) {
                            if (data && data.tags.length > 0) {
                                $(".container_" + sku).html("");
                                $(".container_" + sku).html('<div class="badges-container" style="display: flex; gap: 5px;"></div>');
                                var tags_sku = [];
                                for (let i = 0; i < data.tags.length; i++) {
                                    const tag = data.tags[i];
                                    tags_sku.push('<span id="' + tag.id_tag_sku + '-' + tag.sku + '-produto" style="background-color: ' + tag.cor + '; color: white; padding: 2px 5px; border-radius: 3px; margin-right: 5px;" class="badge badge_tag">' + tag.descricao + '</span>');
                                }

                                // Adicionando os badges antes do input correspondente
                                $(".container_" + sku).find(".badges-container").html(tags_sku.join(" "));
                                Swal.close()
                            } else {
                                $(".container_" + sku).html("");
                                $(".container_" + sku).html('<div class="badges-container" style="display: flex; gap: 5px;"></div>');
                            }

                            console.log(data.tags_venda.length)
                            console.log(data.tags_venda)
                            if (data && data.tags_venda.length > 0) {
                                $(".container_finan_" + sku).html("");
                                $(".container_finan_" + sku).html('<div class="badges-container" style="display: flex; gap: 5px;"></div>');
                                var tags_vendas = [];
                                for (let i = 0; i < data.tags_venda.length; i++) {
                                    const tag = data.tags_venda[i];
                                    tags_vendas.push('<span id="' + tag.id_tag_venda + '-' + tag.order_id + '-venda" style="background-color: ' + tag.cor + '; color: white; padding: 2px 5px; border-radius: 3px; margin-right: 5px;" class="badge badge_tag">' + tag.descricao + '</span>');
                                }

                                // Adicionando os badges antes do input correspondente
                                $(".container_finan_" + sku).find(".badges-container").html(tags_vendas.join(" "));
                            } else {
                                $(".container_finan_" + sku).html("");
                                $(".container_finan_" + sku).html('<div class="badges-container" style="display: flex; gap: 5px;"></div>');
                            }
                        }
                    });
                }
            });
        });

        function formatarDataParaBR(dataISO) {
            if (!dataISO) return '';

            // Separando data e hora
            const [data, hora] = dataISO.split(' ');

            // Separando os componentes da data
            const [ano, mes, dia] = data.split('-');

            // Retornando no formato brasileiro
            return `${dia}/${mes}/${ano}`;
        }


        $("#dadosConferencia").on("click", ".aumentarQtd", function () {
            var produtoId = $(this).attr('id');

            var tipo = $("#tipo_conferencia").val();
            if (tipo == "full") {
                $.ajax({
                    type: "POST",
                    url: base_url + 'MercadoLivreIntegracao/atualizaProdutoFull',
                    data: {
                        "produtoId": produtoId,
                        "tipo": "A"
                    },
                    async: false,
                    dataType: 'json',
                    success: function (data) {
                        var esperado = data.nf;
                        var lido = data.lidos;

                        if (esperado != 0) {
                            var restante = esperado - lido;
                        } else {
                            var restante = "-";
                        }

                        $("#span_esperado_" + produtoId).text("Esperados NF: " + esperado);
                        $("#span_lido_" + produtoId).text("Lidos: " + lido);
                        $("#span_restante_" + produtoId).text("Produtos restantes: " + restante);
                        verificaProdutosRestante(esperado, lido, produtoId)


                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: base_url + 'Integracao/atualizaProduto',
                    data: {
                        "produtoId": produtoId,
                        "tipo": "A"
                    },
                    async: false,
                    dataType: 'json',
                    success: function (data) {
                        var esperado = data.nf;
                        var lido = data.lidos;

                        if (esperado != 0) {
                            var restante = esperado - lido;
                        } else {
                            var restante = "-";
                        }

                        $("#span_esperado_" + produtoId).text("Esperados NF: " + esperado);
                        $("#span_lido_" + produtoId).text("Lidos: " + lido);
                        $("#span_restante_" + produtoId).text("Produtos restantes: " + restante);
                        verificaProdutosRestante(esperado, lido, produtoId)


                    }
                });
            }

        });

        $("#dadosConferencia").on("click", ".removerQtd", function () {
            var produtoId = $(this).attr('id');

            var tipo = $("#tipo_conferencia").val();
            if (tipo == "full") {
                $.ajax({
                    type: "POST",
                    url: base_url + 'MercadoLivreIntegracao/atualizaProdutoFull',
                    data: {
                        "produtoId": produtoId,
                        "tipo": "D"
                    },
                    async: false,
                    dataType: 'json',
                    success: function (data) {
                        var esperado = data.nf;
                        var lido = data.lidos;

                        if (esperado != 0) {
                            var restante = esperado - lido;
                        } else {
                            var restante = "-";
                        }

                        $("#span_esperado_" + produtoId).text("Esperados NF: " + esperado);
                        $("#span_lido_" + produtoId).text("Lidos: " + lido);
                        $("#span_restante_" + produtoId).text("Produtos restantes: " + restante);
                        verificaProdutosRestante(esperado, lido, produtoId)


                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: base_url + 'Integracao/atualizaProduto',
                    data: {
                        "produtoId": produtoId,
                        "tipo": "D"
                    },
                    async: false,
                    dataType: 'json',
                    success: function (data) {
                        var esperado = data.nf;
                        var lido = data.lidos;

                        if (esperado != 0) {
                            var restante = esperado - lido;
                        } else {
                            var restante = "-";
                        }

                        $("#span_esperado_" + produtoId).text("Esperados NF: " + esperado);
                        $("#span_lido_" + produtoId).text("Lidos: " + lido);
                        $("#span_restante_" + produtoId).text("Produtos restantes: " + restante);
                        verificaProdutosRestante(esperado, lido, produtoId)


                    }
                });
            }

        });

        $(".aumentarQtd").on("click", function () {
            var produtoId = $(this).attr('id');

            var tipo = $("#tipo_conferencia").val();
            if (tipo == "full") {
                $.ajax({
                    type: "POST",
                    url: base_url + 'MercadoLivreIntegracao/atualizaProdutoFull',
                    data: {
                        "produtoId": produtoId,
                        "tipo": "A"
                    },
                    async: false,
                    dataType: 'json',
                    success: function (data) {
                        var esperado = data.nf;
                        var lido = data.lidos;

                        if (esperado != 0) {
                            var restante = esperado - lido;
                        } else {
                            var restante = "-";
                        }

                        $("#span_esperado_" + produtoId).text("Esperados NF: " + esperado);
                        $("#span_lido_" + produtoId).text("Lidos: " + lido);
                        $("#span_restante_" + produtoId).text("Produtos restantes: " + restante);
                        verificaProdutosRestante(esperado, lido, produtoId)


                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: base_url + 'Integracao/atualizaProduto',
                    data: {
                        "produtoId": produtoId,
                        "tipo": "A"
                    },
                    async: false,
                    dataType: 'json',
                    success: function (data) {
                        var esperado = data.nf;
                        var lido = data.lidos;

                        if (esperado != 0) {
                            var restante = esperado - lido;
                        } else {
                            var restante = "-";
                        }

                        $("#span_esperado_" + produtoId).text("Esperados NF: " + esperado);
                        $("#span_lido_" + produtoId).text("Lidos: " + lido);
                        $("#span_restante_" + produtoId).text("Produtos restantes: " + restante);
                        verificaProdutosRestante(esperado, lido, produtoId)


                    }
                });
            }



        });

        $(".removerQtd").on("click", function () {
            var produtoId = $(this).attr('id');

            var tipo = $("#tipo_conferencia").val();
            if (tipo == "full") {
                $.ajax({
                    type: "POST",
                    url: base_url + 'MercadoLivreIntegracao/atualizaProdutoFull',
                    data: {
                        "produtoId": produtoId,
                        "tipo": "D"
                    },
                    async: false,
                    dataType: 'json',
                    success: function (data) {
                        var esperado = data.nf;
                        var lido = data.lidos;

                        if (esperado != 0) {
                            var restante = esperado - lido;
                        } else {
                            var restante = "-";
                        }

                        $("#span_esperado_" + produtoId).text("Esperados NF: " + esperado);
                        $("#span_lido_" + produtoId).text("Lidos: " + lido);
                        $("#span_restante_" + produtoId).text("Produtos restantes: " + restante);
                        verificaProdutosRestante(esperado, lido, produtoId)


                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: base_url + 'Integracao/atualizaProduto',
                    data: {
                        "produtoId": produtoId,
                        "tipo": "D"
                    },
                    async: false,
                    dataType: 'json',
                    success: function (data) {
                        var esperado = data.nf;
                        var lido = data.lidos;

                        if (esperado != 0) {
                            var restante = esperado - lido;
                        } else {
                            var restante = "-";
                        }

                        $("#span_esperado_" + produtoId).text("Esperados NF: " + esperado);
                        $("#span_lido_" + produtoId).text("Lidos: " + lido);
                        $("#span_restante_" + produtoId).text("Produtos restantes: " + restante);
                        verificaProdutosRestante(esperado, lido, produtoId)


                    }
                });
            }



        });

        function verificaProdutosRestante(qtd_nf, lido, produtoId) {
            if (qtd_nf != "0" && qtd_nf != undefined) {
                if (parseInt(qtd_nf) != parseInt(lido)) {

                    $("#div_restante_" + produtoId).css({ "background-color": "#f56262" });
                    $("#div_restante_" + produtoId).css({ "border-color": "#f56262" });
                } else {
                    console.log("igual")
                    $("#div_restante_" + produtoId).css({ "background-color": "#686ee4" });
                    $("#div_restante_" + produtoId).css({ "border-color": "#686ee4" });
                }
            }

        }

        $("#buscar_divergencia").on("click", function () {
            /* $("#campo_pesquisa").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $(".div_principal").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
    
                $(".div_principalHr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
    
            }); */
            $("#campo_pesquisa").val("Divergência nos valores de frete").trigger("keyup")


        });

        $("#imprimir_etiqueta").on("click", function () {
            var arquivoSelecionado = $('#arquivo_selecionado').val();
            window.open("/MercadoLivreIntegracao/GestaoEstoqueImprimir/" + arquivoSelecionado, "_blank");
        });

        $('#limpar_arquivo_especifico').click(function() {
            // Pega o arquivo selecionado no filtro
            var arquivoSelecionado = $('#arquivo_selecionado').val();
            
            if (!arquivoSelecionado) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'Selecione um arquivo para limpar!',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }
            
            Swal.fire({
                title: 'Confirmar limpeza',
                text: 'Tem certeza que deseja limpar este arquivo específico? Esta ação não pode ser desfeita.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, limpar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/MercadoLivreIntegracao/limparArquivoTemporario',
                        type: 'POST',
                        data: {
                            arquivo_id: arquivoSelecionado
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso!',
                                    text: 'Arquivo limpo com sucesso!',
                                    confirmButtonColor: '#28a745'
                                }).then(() => {
                                    // Recarrega a página para atualizar os dados
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro!',
                                    text: 'Erro ao limpar arquivo: ' + response.message,
                                    confirmButtonColor: '#d33'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: 'Erro ao processar requisição!',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        });

        $("#limpar_tabela").on("click", function () {
            console.log("click")

            var tipo = $("#tipo_conferencia").val()

            var urlDados = '/Integracao/limpaTabelaSeparacao';
            if (tipo != undefined && tipo == "P") {
                var urlDados = '/Integracao/limpaTabelaSeparacaoConferencia';
            } else if (tipo != undefined && tipo == "full") {
                urlDados = '/MercadoLivreIntegracao/limpaTabelaFull';
            }

            Swal.fire({
                title: "Tem certeza que deseja excluir?",
                text: "Isso removera todas informações dos produtos conferidos",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sim, Apagar!",
                cancelButtonText: "Não, Cancelar!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = urlDados;
                }
            });



        });

        $("#atualizar_estoque_bling").on("click", function () {
            var produtoTMP = $("#produtoTMP").val();
            var observacoes = $("#observacoes").val();


            var cont = 0;
            var array = [];
            $('.div_principal.selected').each(function () {
                var id = $(this).find('.id_conferencia').attr("id");
                var number = id.split('_').pop(); // Pega a última parte da string após o "_"
                array.push(number)
                cont++
            });

            if (array.length < 1) {
                $.ajax({
                    type: "POST",
                    url: base_url + 'Integracao/atualizaEstoquebling',
                    data: {
                        "produtoId": produtoTMP,
                        "observacoes": observacoes
                    },
                    async: false,
                    dataType: 'json',
                    success: function (data) {

                        if (data == "sucesso") {
                            Swal.fire({
                                title: 'Sucesso',
                                text: "Estoque atualizado com sucesso",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.close()
                                }
                            })
                        } else {
                            Swal.fire({
                                title: 'Erro',
                                text: data,
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


                    }
                });
            } else {

                $.ajax({
                    type: "POST",
                    url: base_url + 'Integracao/atualizaEstoquebling',
                    data: {
                        "produtoId": array[0],
                        "observacoes": observacoes
                    },
                    async: false,
                    dataType: 'json',
                    success: function (data) {

                        if (data == "sucesso") {
                            Swal.fire({
                                title: 'Sucesso',
                                text: "Estoque atualizado com sucesso",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.close()
                                }
                            })
                        } else {
                            Swal.fire({
                                title: 'Erro',
                                text: data,
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


                    }
                });
            }





        });


        $("#imprimir_conferencia").on("click", function () {
            // Aplicar estilos temporários antes de iniciar a impressão
            var style = $('<style>@media print { @page { size: landscape; } }</style>');

            // Iniciar a impressão
            window.print();

            // Remover o estilo temporário após a impressão
            style.remove();

        });

        $("#excluir_multiplos_itens").on("click", function () {
            var produtoId = $(this).attr('id');

            Swal.fire({
                title: "Tem certeza que deseja excluir?",
                text: "Isso removera todas informações dos produtos selecionados",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sim, Apagar!",
                cancelButtonText: "Não, Cancelar!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var dados_produto = [];
                    var x = 0;
                    $('.div_principal.selected').each(function () {
                        if ($(this).find('.input_id_produto').val() != "0" && $(this).find('.input_id_produto').val() != "") {

                            dados_produto[x] = { "id_produto": $(this).find('.input_id_produto').val().trim() }
                        }

                        x++;
                    });


                    if (dados_produto.length > 0) {


                        // Depois que o loop termina
                        for (let i = 0; i < dados_produto.length; i++) {
                            var produtoId = dados_produto[i].id_produto;



                            $.ajax({
                                type: "POST",
                                url: base_url + 'Integracao/excluirItem',
                                data: {
                                    "produtoId": produtoId
                                },
                                async: false,
                                dataType: 'json',
                                success: function (data) {
                                    if (data.resposta == "sucesso") {

                                    }
                                }
                            });
                        }

                        // Redireciona somente depois que o for termina
                        window.location.href = '/Integracao/blingMercadorias?tipo_msg=sucesso&msg=Ação realizada!';
                    } else {
                        Swal.fire({
                            title: 'Erro',
                            text: "Ocorreu algum erro ao realizar a exclusão! Nenhum item selecionado.",
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
                }
            });



        });

        $("#dadosConferencia").on("click", ".removerProduto", function () {
            var produtoId = $(this).attr('id');

            var tipo = $("#tipo_conferencia").val();
            if (tipo == "full") {

                Swal.fire({
                    title: "Tem certeza que deseja excluir?",
                    text: "Isso removera todas informações do produto conferido",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim, Apagar!",
                    cancelButtonText: "Não, Cancelar!",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {


                        $.ajax({
                            type: "POST",
                            url: base_url + 'MercadoLivreIntegracao/excluirItemFull',
                            data: {
                                "produtoId": produtoId
                            },
                            async: false,
                            dataType: 'json',
                            success: function (data) {
                                if (data.resposta == "sucesso") {

                                    window.location.href = '/MercadoLivreIntegracao/GestaoEstoque?tipo_msg=sucesso&msg=Ação realizada!';
                                }
                            }
                        });
                    }
                });
            } else {

                Swal.fire({
                    title: "Tem certeza que deseja excluir?",
                    text: "Isso removera todas informações do produto conferido",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim, Apagar!",
                    cancelButtonText: "Não, Cancelar!",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {


                        $.ajax({
                            type: "POST",
                            url: base_url + 'Integracao/excluirItem',
                            data: {
                                "produtoId": produtoId
                            },
                            async: false,
                            dataType: 'json',
                            success: function (data) {
                                if (data.resposta == "sucesso") {

                                    window.location.href = '/Integracao/blingMercadorias?tipo_msg=sucesso&msg=Ação realizada!';
                                }
                            }
                        });
                    }
                });
            }




        });


        $(".removerProduto").on("click", function () {
            var produtoId = $(this).attr('id');

            var tipo = $("#tipo_conferencia").val()



            if (tipo == "full") {

                Swal.fire({
                    title: "Tem certeza que deseja excluir?",
                    text: "Isso removera todas informações do produto conferido",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim, Apagar!",
                    cancelButtonText: "Não, Cancelar!",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {


                        $.ajax({
                            type: "POST",
                            url: base_url + 'MercadoLivreIntegracao/excluirItemFull',
                            data: {
                                "produtoId": produtoId
                            },
                            async: false,
                            dataType: 'json',
                            success: function (data) {
                                if (data.resposta == "sucesso") {

                                    window.location.href = '/MercadoLivreIntegracao/GestaoEstoque?tipo_msg=sucesso&msg=Ação realizada!';
                                }
                            }
                        });
                    }
                });
            } else {

                var urlDados = 'Integracao/excluirItem';
                if (tipo != undefined && tipo == "P") {
                    var urlDados = '/Integracao/excluirItemConferencia';
                }

                Swal.fire({
                    title: "Tem certeza que deseja excluir?",
                    text: "Isso removera todas informações do produto conferido",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim, Apagar!",
                    cancelButtonText: "Não, Cancelar!",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {


                        $.ajax({
                            type: "POST",
                            url: base_url + urlDados,
                            data: {
                                "produtoId": produtoId
                            },
                            async: false,
                            dataType: 'json',
                            success: function (data) {
                                if (data.resposta == "sucesso") {
                                    window.location.href = '/Integracao/blingMercadorias?tipo_msg=sucesso&msg=Ação realizada!';
                                }
                            }
                        });
                    }
                });
            }



        });

        /** JS ML */

        var url = window.location.pathname

        if (url.includes("/MercadoLivreIntegracao/SincronizarML/")) {

            $(".div_sincronizado_produto").hide();
            $(".div_sincronizando_produto").hide();
            $(".div_sincronizado_pedido").hide();
            $(".div_sincronizando_pedido").hide();

            var id = $("#id_conta").val();

            $.ajax({
                type: "POST",
                url: base_url + '/MercadoLivreIntegracao/getStatusSincronizacao',
                data: {
                    'id': id,
                },
                dataType: 'json',
                success: async function (data) {
                    if (parseInt(data.sincronizacao_produto) == 1) {
                        $(".div_sincronizado_produto").show();
                        $(".div_sincronizando_produto").hide();
                    } else {
                        $(".div_sincronizado_produto").hide();
                        $(".div_sincronizando_produto").show();
                        console.log("entrou no else")

                        //se for status 2, ja esta sicnronizando
                        if (parseInt(data.sincronizacao_produto) != 2) {
                            $.ajax({
                                type: "POST",
                                url: base_url + '/MercadoLivreIntegracao/getSincronizacaoProduto',
                                data: {
                                    'id': id,
                                },
                                dataType: 'json',
                                success: async function (data) {
                                }
                            });
                        }

                    }

                    if (parseInt(data.sincronizacao_pedido) == 1) {
                        $(".div_sincronizado_pedido").show();
                        $(".div_sincronizando_pedido").hide();
                    } else {
                        $(".div_sincronizado_pedido").hide();
                        $(".div_sincronizando_pedido").show();

                        //se for status 2, ja esta sicnronizando
                        if (parseInt(data.sincronizacao_pedido) != 2) {
                            $.ajax({
                                type: "POST",
                                url: base_url + '/MercadoLivreIntegracao/getSincronizacaoPedido',
                                data: {
                                    'id': id,
                                },
                                dataType: 'json',
                                success: async function (data) {
                                }
                            });
                        }


                    }
                }
            });

            setInterval(async () => {


                $.ajax({
                    type: "POST",
                    url: base_url + '/MercadoLivreIntegracao/getStatusSincronizacao',
                    data: {
                        'id': id,
                    },
                    dataType: 'json',
                    success: async function (data) {
                        if (parseInt(data.sincronizacao_produto) == 1) {
                            $(".div_sincronizado_produto").show();
                            $(".div_sincronizando_produto").hide();
                        }
                        if (parseInt(data.sincronizacao_pedido) == 1) {
                            $(".div_sincronizado_pedido").show();
                            $(".div_sincronizando_pedido").hide();
                        }
                    }
                });

            }, 10000);
        }

        /** JS ML FIM*/

        async function carregarContasBling(firstDay, formattedLastDay, ano, tabId, situacoes_filtro, mes_ref) {
            try {
                // Faz o request para getContasBling
                const data = await $.ajax({
                    type: "POST",
                    url: base_url + '/Integracao/getContasBling',
                    data: {
                        'data_inicio': firstDay,
                        'data_final': formattedLastDay
                    },
                    dataType: 'json'
                });



                // Se os dados foram retornados, carregue os gráficos
                if (data) {
                    const graficoCategoriaPromise = $.ajax({
                        type: "POST",
                        url: base_url + '/Integracao/getDadosGraficoCategoria',
                        data: {
                            'ano': ano,
                            'mes': tabId,
                            'situacoes_filtro': situacoes_filtro
                        },
                        dataType: 'json'
                    }).then(data => {
                        var total_categoria = data.reduce((sum, item) => sum + parseFloat(item.total_valor || 0), 0);

                        $("#titulo_categoria_" + mes_ref).html("")
                        $("#titulo_categoria_" + mes_ref).html("Categorias - Total: R$ " + formatNumber(total_categoria.toFixed(2)))

                        let chartData = data.map(item => ({
                            "country": item.descricao,
                            "litres": parseFloat(item.total_valor)
                        }));



                        am4core.ready(function () {
                            am4core.useTheme(am4themes_animated);

                            var chart = am4core.create("grafico_" + mes_ref + "_categoria", am4charts.PieChart3D);
                            chart.hiddenState.properties.opacity = 0;

                            chart.legend = new am4charts.Legend();
                            chart.legend.position = "top";

                            chart.data = chartData;

                            var series = chart.series.push(new am4charts.PieSeries3D());
                            series.dataFields.value = "litres";
                            series.dataFields.category = "country";
                        });
                    });

                    const graficoContatoPromise = $.ajax({
                        type: "POST",
                        url: base_url + '/Integracao/getDadosGraficoContato',
                        data: {
                            'ano': ano,
                            'mes': tabId,
                            'situacoes_filtro': situacoes_filtro
                        },
                        dataType: 'json'
                    }).then(data => {
                        var total_contato = data.reduce((sum, item) => sum + parseFloat(item.total_valor || 0), 0);
                        $("#titulo_contato_" + mes_ref).html("")
                        $("#titulo_contato_" + mes_ref).html("Contatos - Total: R$ " + formatNumber(total_contato.toFixed(2)))

                        let chartData = data.map(item => ({
                            "country": item.descricao,
                            "litres": parseFloat(item.total_valor)
                        }));

                        am4core.ready(function () {
                            am4core.useTheme(am4themes_animated);

                            var chart = am4core.create("grafico_" + mes_ref + "_contato", am4charts.PieChart3D);
                            chart.hiddenState.properties.opacity = 0;

                            chart.legend = new am4charts.Legend();

                            chart.data = chartData;

                            var series = chart.series.push(new am4charts.PieSeries3D());
                            series.dataFields.value = "litres";
                            series.dataFields.category = "country";
                        });
                    });

                    await Promise.all([graficoCategoriaPromise, graficoContatoPromise]);

                }
            } catch (error) {
                console.error("Erro ao carregar contas ou gráficos:", error);
            }
        }

        $("#adicionarContaML").on("click", function () {
            $.ajax({
                type: "POST",
                url: base_url + '/MercadoLivreIntegracao/salvarContaML',
                data: {},
                dataType: 'json',
                success: async function (data) {
                    console.log(data);

                    let contaHtml = `
                        <div id="div_principal_${data.id_inserido}" class="row">
                            <div class="col-md-3">
                                <h5>Descrição</h5>
                                <div class="controls">
                                    <input type="text" id="codigo_fornecedor_${data.id_inserido}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5>Nome da conta</h5>
                                <div class="controls">
                                    <input disabled type="text" id="codigo_fornecedor_${data.id_inserido}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-1 div_conectado_${data.id_inserido}">
                                <h5 style="display: flex; align-items: center; margin-top: 30px">
                                    Conectado 
                                    <img style="width: 30px; height: 30px; margin-left: 10px;" src="/template/images/checked.png" alt="">
                                </h5>
                            </div>
                            <div style="margin-right: 10px;" class="col-md-1 div_desconectado_${data.id_inserido}">
                                <h5 style="display: flex; align-items: center; margin-top: 30px">
                                    Desconectado 
                                    <img style="width: 30px; height: 30px; margin-left: 10px;" src="/template/images/unchecked.png" alt="">
                                </h5>
                            </div>
                            <div style="margin-left: 30px; display: flex;" class="col-md-2">
                                <a href="${data.url_redirect}" style="margin-top: 22px; margin-right: 10px" 
                                   id="adicionarContaML_${data.id_inserido}" 
                                   class="btnAdicionarContaML btn btn-radius btn-primary">Conectar conta</a>
                                <button style="margin-top: 22px" 
                                        id="sincronizarML_${data.id_inserido}"  
                                        class="btnSincronizarML btn btn-success btn-danger">Sincronizar dados</button>
                                <button style="margin-top: 22px" 
                                        id="removerContaML_${data.id_inserido}"  
                                        class="btnRemoverContaML btn btn-radius btn-danger">Remover conta</button>

                            </div>
                        </div>
                    `;

                    $("#divContasML").append(contaHtml);

                    // Esconde os elementos logo após adicioná-los ao DOM
                    $(`.div_conectado_${data.id_inserido}`).hide();
                    $(`#sincronizarML_${data.id_inserido}`).hide();
                }
            });
        });

        // Delegação de eventos para os botões de remoção
        $("#divContasML").on("click", ".btnRemoverContaML", function () {
            var id = $(this).attr('id').replace("removerContaML_", "");
            console.log("Removendo ID:", id);

            // Remove o elemento visualmente
            $("#div_principal_" + id).remove();

            // Faz a requisição AJAX para remover do backend
            $.ajax({
                type: "POST",
                url: base_url + '/MercadoLivreIntegracao/removerContaML',
                data: { 'id': id },
                dataType: 'json',
                success: async function (data) {
                    console.log("Conta removida:", data);
                }
            });
        });

        $(".descricao_conta").on('blur', function () {
            var id = $(this).attr('id').replace("descricao_conta_", "");
            var valorCampo = $(this).val();

            // Faz a requisição AJAX para remover do backend
            $.ajax({
                type: "POST",
                url: base_url + '/MercadoLivreIntegracao/atualizarContaML',
                data: {
                    'id': id,
                    'valorCampo': valorCampo
                },
                dataType: 'json',
                success: async function (data) {
                }
            });
        })

        function calculaValorLucro(loja, mes_ref) {
            setTimeout(() => {

                function getValue(selector) {
                    var value = $(selector).text().trim();
                    value = value.replace(/[^\d,.-]/g, '')

                    if (/,/.test(value)) {
                        numericValue = parseFormattedNumber(value)
                    } else {
                        var numericValue = value.replace(',', '.'); // Remove caracteres não numéricos, exceto "," e "." e converte para formato numérico
                    }
                    if (!numericValue) return 0; // Retorna 0 se o valor não existe
                    return parseFloat(numericValue) || 0; // Tenta converter para número, retorna 0 se falhar
                }

                const valor_venda = getValue(`#valor_venda_${loja}_${mes_ref}`);
                const custo_produto = getValue(`#custo_produto_${loja}_${mes_ref}`);
                const desconto_loja = getValue(`#desconto_loja_${loja}_${mes_ref}`);
                const total_mkt = getValue(`#total_mkt_${loja}_${mes_ref}`);
                const frete_vendedor = getValue(`#frete_vendedor_${loja}_${mes_ref}`);
                const valor_ads = getValue(`#valor_ads_${loja}_div_${mes_ref}`);

                if (valor_ads != 0 && valor_ads != "" && valor_ads != null) {
                    var valor_ads_perc = (valor_ads / parseFloat(valor_venda)) * 100;
                    $(`#perc_ads_${loja}_${mes_ref}`).text(`${valor_ads_perc.toFixed(2)}%`);
                }



                var taxa_fixa = getValue(`#taxa_fixa_${loja}_${mes_ref}`);

                const selectedValue = $('input[name="radioTaxaFixa"]:checked').val();
                if (selectedValue == "0") {
                    var lucro_bruto = valor_venda - custo_produto - desconto_loja - total_mkt - frete_vendedor - valor_ads;
                } else {
                    var lucro_bruto = valor_venda - custo_produto - desconto_loja - total_mkt - frete_vendedor - valor_ads - taxa_fixa;

                }

                var perc_lucro_bruto = 0;
                if (lucro_bruto != 0) {
                    perc_lucro_bruto = (lucro_bruto / valor_venda) * 100;
                } else {
                    perc_lucro_bruto = 0;
                }


                $(`#lucro_bruto_${loja}_${mes_ref}`).text(`R$ ${lucro_bruto.toFixed(2)}`);
                $(`#perc_lucro_bruto_${loja}_${mes_ref}`).text(`${perc_lucro_bruto.toFixed(2)}%`);

                const ticket_medio = getValue(`#tickt_medio_${loja}_${mes_ref}`);
                const qtd_pedido = getValue(`#qtd_pedidos_${loja}_${mes_ref}`);

                atualizaValorTotal(mes_ref, qtd_pedido, custo_produto, desconto_loja, valor_venda, ticket_medio, total_mkt, frete_vendedor, valor_ads, taxa_fixa, lucro_bruto.toFixed(2))
            }, 1500);
        }

        function atualizaValorTotal(mes_ref, qtd_pedido_novo, custo_produto_novo, desconto_loja_novo, valor_venda_novo, ticket_medio_novo, total_mkt_novo, frete_vendedor_novo, valor_ads, taxa_fixa_novo, lucro_bruto_novo) {
            function getValue(selector) {
                const value = $(selector).text().trim();
                if (!value) return 0; // Retorna 0 se o valor não existe
                const numericValue = value.replace(/[^\d,.-]/g, '').replace(',', '.'); // Remove caracteres não numéricos, exceto "," e "." e converte para formato numérico
                return parseFloat(numericValue) || 0; // Tenta converter para número, retorna 0 se falhar
            }

            var total_pedidos = getValue(`#total_pedidos_${mes_ref}`);
            total_pedidos = total_pedidos + parseFloat(qtd_pedido_novo);
            $(`#total_pedidos_${mes_ref}`).text(`${total_pedidos}`);

            var custo_total = getValue(`#custo_total_${mes_ref}`);
            custo_total = custo_total + parseFloat(custo_produto_novo);
            $(`#custo_total_${mes_ref}`).text(`R$ ${custo_total.toFixed(2)}`);
            $(`#total_custo_produto_${mes_ref}`).text(`R$ ${formatNumber(custo_total.toFixed(2))}`);

            var desconto_loja = getValue(`#desconto_loja_${mes_ref}`);
            desconto_loja = desconto_loja + parseFloat(desconto_loja_novo);
            $(`#desconto_loja_${mes_ref}`).text(`R$ ${desconto_loja.toFixed(2)}`);
            $(`#total_desconto_marketplaces_${mes_ref}`).text(`R$ ${formatNumber(desconto_loja.toFixed(2))}`);

            var valor_venda = getValue(`#valor_venda_${mes_ref}`);
            valor_venda = valor_venda + parseFloat(valor_venda_novo);
            $(`#valor_venda_${mes_ref}`).text(`R$ ${valor_venda.toFixed(2)}`);
            $(`#total_vendas_marketplaces_${mes_ref}`).text(`R$ ${formatNumber(valor_venda.toFixed(2))}`);


            var ticket_medio = getValue(`#ticket_medio_${mes_ref}`);
            ticket_medio = ticket_medio + parseFloat(ticket_medio_novo);
            $(`#ticket_medio_${mes_ref}`).text(`R$ ${ticket_medio.toFixed(2)}`);

            var taxa_marketplace = getValue(`#taxa_marketplace_${mes_ref}`);
            taxa_marketplace = taxa_marketplace + parseFloat(total_mkt_novo);
            $(`#taxa_marketplace_${mes_ref}`).text(`R$ ${taxa_marketplace.toFixed(2)}`);

            var frete_vendedor = getValue(`#frete_vendedor_${mes_ref}`);
            frete_vendedor = frete_vendedor + parseFloat(frete_vendedor_novo);
            $(`#frete_vendedor_${mes_ref}`).text(`R$ ${frete_vendedor.toFixed(2)}`);

            var ads = getValue(`#ads_${mes_ref}`);
            ads = ads + parseFloat(valor_ads);
            $(`#ads_${mes_ref}`).text(`R$ ${ads.toFixed(2)}`);

            var taxa_fixa = getValue(`#taxa_fixa_${mes_ref}`);
            taxa_fixa = taxa_fixa + parseFloat(taxa_fixa_novo);
            $(`#taxa_fixa_${mes_ref}`).text(`R$ ${taxa_fixa.toFixed(2)}`);

            var lucro_bruto = getValue(`#lucro_bruto_${mes_ref}`);
            lucro_bruto = lucro_bruto + parseFloat(lucro_bruto_novo);
            $(`#lucro_bruto_${mes_ref}`).text(`R$ ${lucro_bruto.toFixed(2)}`);
            $(`#total_lucro_bruto_sem_imposto_${mes_ref}`).text(`R$ ${formatNumber(lucro_bruto.toFixed(2))}`);


            //Percentuais


            var taxa_marketplace = getValue(`#taxa_marketplace_${mes_ref}`);
            var frete_vendedor = getValue(`#frete_vendedor_${mes_ref}`);
            var valor_venda = getValue(`#valor_venda_${mes_ref}`);

            var percentual_custo_frete = (frete_vendedor / valor_venda) * 100;
            $(`#percentual_custo_frete_${mes_ref}`).text(`${percentual_custo_frete.toFixed(2)}%`);

            var lucro_bruto = getValue(`#lucro_bruto_${mes_ref}`);
            var perc_lucro_bruto = (lucro_bruto / valor_venda) * 100;
            $(`#percentual_lucro_bruto_${mes_ref}`).text(`${perc_lucro_bruto.toFixed(2)}%`);

            var perc_custo_total = (taxa_marketplace / valor_venda) * 100;
            $(`#percentual_custo_${mes_ref}`).text(`${perc_custo_total.toFixed(2)}%`);

            setTimeout(() => {

                var filtro_ano = $("#filtro_ano").val();
                var mes_numero = obterNumero(mes_ref)

                $.ajax({
                    type: "POST",
                    url: base_url + '/Integracao/getDadosMesDre',
                    data: {
                        'ano': filtro_ano,
                        'mes': mes_ref,
                        'mes_numero': mes_numero
                    },
                    dataType: 'json',
                    success: async function (data) {



                        if (data && data.visao_estoque) {
                            const estoque = data.visao_estoque;

                            // Definir valores padrão caso alguma propriedade esteja ausente
                            const qtdItens = estoque.qtd_itens ?? 0;
                            const unidades = estoque.unidades ?? 0;
                            const valorEstoque = estoque.valor ?? 0.0;

                            // Atualizar os inputs com validação
                            $("#qtd_itens_estoque_" + mes_ref).val(qtdItens);
                            $("#unidades_" + mes_ref).val(unidades);
                            $("#valor_estoque_" + mes_ref).val(valorEstoque);

                            // Atualizar os elementos de exibição com validação
                            $("#qtd_itens_estoque_div_" + mes_ref).html(qtdItens);
                            $("#unidades_div_" + mes_ref).html(unidades);
                            $("#valor_estoque_div_" + mes_ref).html("R$ " + valorEstoque).trigger("click");
                        } else {
                            console.warn("Dados de estoque não disponíveis.");
                        }



                        // Renderiza as despesas variadas em uma tabela
                        const $containerVariadas = $(".despesas-container");
                        $containerVariadas.empty();

                        let tabelaVariadasHTML = `
                        <table style="width: 430px" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Identificação</th>
                                    <th>Valor</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                        `;

                        var total_despesas_variadas = 0;
                        data.despesas_variadas.forEach(variada => {
                            total_despesas_variadas += parseFloat(variada.valor.replace(",", "."));
                            tabelaVariadasHTML += `
                                <tr data-toggle="modal" data-target=".bd-example-modal-lg" >
                                    <td>${variada.identificacao}</td>
                                    <td>R$ ${formatNumber(variada.valor)}</td>
                                    <td id="${variada.id_despesas_fixas}" >
                                        <a href="/Integracao/excluirDadosDRE/${variada.id_despesas_fixas}">
                                            <i style="color: #b30000; font-size: 20px!important" class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            `;
                        });

                        tabelaVariadasHTML += `</tbody></table>`;
                        $containerVariadas.append(tabelaVariadasHTML);


                        // Renderiza as despesas fixas em uma tabela
                        const $containerFixa = $(".despesas-fixa");
                        $containerFixa.empty();

                        let tabelaFixaHTML = `
                        <table style="width: 430px" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Identificação</th>
                                    <th>Valor</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                        `;

                        var total_despesas_fixas = 0;
                        data.despesas_fixa.forEach(fixa => {
                            total_despesas_fixas += parseFloat(fixa.valor.replace(",", "."));
                            tabelaFixaHTML += `
                                <tr data-toggle="modal" data-target=".bd-example-modal-lg" >
                                    <td>${fixa.identificacao}</td>
                                    <td>R$ ${formatNumber(fixa.valor)}</td>
                                    <td id="${fixa.id_despesas_fixas}" >
                                        <a href="/Integracao/excluirDadosDRE/${fixa.id_despesas_fixas}">
                                            <i style="color: #b30000; font-size: 20px!important" class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            `;
                        });

                        tabelaFixaHTML += `</tbody></table>`;
                        $containerFixa.append(tabelaFixaHTML);

                        // Renderiza os impostos em uma tabela
                        const $containerImpostos = $(".impostos_div");
                        $containerImpostos.empty();

                        let tabelaImpostosHTML = `
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Identificação</th>
                                    <th>Valor</th>
                                    <th>%</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                        `;

                        var total_despesas_impostos = 0;
                        data.despesas_imposto.forEach(imposto => {
                            total_despesas_impostos += parseFloat(imposto.valor.replace(",", "."));
                            var percentualImposto = (parseFloat(imposto.valor.replace("R$ ", "")) / valor_venda) * 100;
                            tabelaImpostosHTML += `
                                <tr data-toggle="modal" data-target=".bd-example-modal-lg" >
                                    <td>${imposto.identificacao}</td>
                                    <td>R$ ${formatNumber(imposto.valor)}</td>
                                    <td id="${imposto.id_despesas_fixas}"  >${percentualImposto.toFixed(2)}%</td>
                                    <td >
                                        <a href="/Integracao/excluirDadosDRE/${imposto.id_despesas_fixas}">
                                            <i style="color: #b30000; font-size: 20px!important" class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            `;
                        });

                        tabelaImpostosHTML += `</tbody></table>`;
                        $containerImpostos.append(tabelaImpostosHTML);


                        var total_despesas_final = total_despesas_fixas + total_despesas_variadas;
                        var total_lucro_bruto_imposto = lucro_bruto - total_despesas_impostos;
                        var percentual_total_lucro_bruto_imposto = (total_lucro_bruto_imposto / valor_venda) * 100;
                        var total_lucro_liquido = total_lucro_bruto_imposto - total_despesas_final;
                        var percentual_total_lucro_liquido = (total_lucro_liquido / valor_venda) * 100;


                        $("#total_var_despesa_" + mes_ref).text(`R$ ${total_despesas_variadas.toFixed(2)}`);
                        $("#total_fixa_despesa_" + mes_ref).text(`R$ ${total_despesas_fixas.toFixed(2)}`);
                        $("#total_imposto_" + mes_ref).text(`R$ ${total_despesas_impostos.toFixed(2)}`);

                        $(`#total_lucro_bruto_com_imposto_${mes_ref}`).text(`R$ ${formatNumber(total_lucro_bruto_imposto.toFixed(2))}`);
                        $(`#total_despesas_${mes_ref}`).text(`R$ ${formatNumber(total_despesas_final.toFixed(2))}`);
                        $(`#margem_lucro_bruto_com_impostos_${mes_ref}`).text(`${percentual_total_lucro_bruto_imposto.toFixed(2)}%`);
                        $(`#lucro_liquido_${mes_ref}`).text(`R$ ${formatNumber(total_lucro_liquido.toFixed(2))}`);
                        $(`#margem_lucro_liquida_${mes_ref}`).text(`${percentual_total_lucro_liquido.toFixed(2)}%`);

                        formatCurrency();

                    }
                });

                formatCurrency();

            }, 3000);

        }

        // Captura o clique na linha da tabela
        $(document).on("click", "tr[data-toggle='modal']", function () {
            // Obtém os valores das células da linha clicada
            var identificacao = $(this).find("td:eq(0)").text().trim();
            var valor = $(this).find("td:eq(1)").text().replace("R$ ", "").trim();
            var id = $(this).find("td:eq(2)").attr('id');

            // Preenche os campos da modal
            $("#identificacao").val(identificacao);
            $("#valor_dado").val(valor);
            $("#id_dado_dre").val(id);

        });


        $(".situacoes_devolucao").on('change', function () {
            var situacoes_devolucao = $(this).val();


            var mes = $("#mes").val();
            var mes_numero = obterNumero(mes)
            var filtro_ano = $("#filtro_ano").val();


            if (filtro_ano != "") {

                $.ajax({
                    type: "POST",
                    url: base_url + '/Integracao/getDadosMesDevolucao',
                    data: {
                        'ano': filtro_ano,
                        'mes': mes_numero,
                        'situacoes_filtro': situacoes_devolucao
                    },
                    dataType: 'json',
                    success: async function (data) {
                        var qtd_devolucao = data.valor_total;
                        $(`#total_devolucoes_${mes}`).text(qtd_devolucao);
                    }
                });


            }
        })

        $(".dados_visao_financeira").on('change', function () {

            var mes = $("#mes").val();
            var filtro_ano = $("#filtro_ano").val();

            var qtd_itens_estoque = $("#qtd_itens_estoque_" + mes).val();
            var unidades_div = $("#unidades_" + mes).val();
            var valor_estoque_div = $("#valor_estoque_" + mes).val();

            var mes_numero = obterNumero(mes)



            if (filtro_ano != "") {

                $.ajax({
                    type: "POST",
                    url: base_url + '/Integracao/salvarVisaoFinanceiraEstoque',
                    data: {
                        'filtro_ano': filtro_ano,
                        'mes': mes_numero,
                        'qtd_itens_estoque': qtd_itens_estoque,
                        'unidades_div': unidades_div,
                        'valor_estoque_div': valor_estoque_div
                    },
                    dataType: 'json',
                    success: async function (data) {
                        var qtd_devolucao = data.valor_total;
                        $(`#total_devolucoes_${mes}`).text(qtd_devolucao);
                    }
                });


            }
        })

        $(".dados_custo_precificacao").on('change', function () {

            var gnre = $("#gnre").val();
            var embalagem = $("#embalagem").val();
            var coleta_full = $("#coleta_full").val();
            var outros = $("#outros").val();

            $.ajax({
                type: "POST",
                url: base_url + '/Integracao/salvarDadosCusto',
                data: {
                    'gnre': gnre.replace("R$ ", "").replace(",", "."),
                    'embalagem': embalagem.replace("R$ ", "").replace(",", "."),
                    'coleta_full': coleta_full.replace("R$ ", "").replace(",", "."),
                    'outros': outros.replace("R$ ", "").replace(",", "."),
                },
                dataType: 'json',
                success: async function (data) {

                }
            });
        })

        $.ajax({
            type: "POST",
            url: '/Integracao/getDadosCusto',
            data: {
            },
            dataType: 'json',
            success: async function (data) {

                $("#gnre_div").html("R$ " + data.gnre.replace(".", ","));
                $("#gnre").val(data.gnre);
                $("#embalagem_div").html("R$ " + data.embalagem.replace(".", ","));
                $("#embalagem").val(data.embalagem);
                $("#coleta_full_div").html("R$ " + data.custo_full.replace(".", ","));
                $("#coleta_full").val(data.custo_full);
                $("#outros_div").html("R$ " + data.outros.replace(".", ","));
                $("#outros").val(data.outros);
            }
        });

        $(".valor_ads").on('blur', function () {


            var valor = $(this).val().replace("R$ ", "");
            if (valor != undefined && valor != "") {
                valor = parseFloat(valor)
                var referencia = $(this).attr('id').replace("valor_ads_", "")
                referencia = referencia.split("_")
                var loja = referencia[0]
                var mes_ref = referencia[1]

                var filtro_ano = $("#filtro_ano").val();

                var mes_numero = obterNumero(mes_ref)


                $.ajax({
                    type: "POST",
                    url: base_url + '/Integracao/salvarADS',
                    data: {
                        'filtro_ano': filtro_ano || '',
                        'mes': mes_numero || '',
                        'valor': valor || '',
                        'loja': loja || ''
                    },
                    dataType: 'json',
                    success: function (data) {

                    }
                });

                $(`#total_pedidos_${mes_ref}`).text(``);
                $(`#custo_total_${mes_ref}`).text(`R$ 0`);
                $(`#desconto_loja_${mes_ref}`).text(`R$ 0`);
                $(`#valor_venda_${mes_ref}`).text(`R$ 0`);
                $(`#ticket_medio_${mes_ref}`).text(`R$ 0`);
                $(`#taxa_marketplace_${mes_ref}`).text(`R$ 0`);
                $(`#frete_vendedor_${mes_ref}`).text(`R$ 0`);
                $(`#ads_${mes_ref}`).text(`R$ 0`);
                $(`#taxa_fixa_${mes_ref}`).text(`R$ 0`);
                $(`#lucro_bruto_${mes_ref}`).text(`R$ 0`);
                $(`#percentual_custo_frete_${mes_ref}`).text(`0`);
                $(`#percentual_lucro_bruto_${mes_ref}`).text(`0`);
                $(`#percentual_custo_${mes_ref}`).text(`0`);

                $("#valor_ads_" + loja + "_div_" + mes_ref).html("R$ " + valor);

                function getValue(selector) {
                    const value = $(selector).text().trim();
                    if (!value) return 0; // Retorna 0 se o valor não existe
                    const numericValue = value.replace(/[^\d,.-]/g, '').replace(',', '.'); // Remove caracteres não numéricos, exceto "," e "." e converte para formato numérico
                    return parseFloat(numericValue) || 0; // Tenta converter para número, retorna 0 se falhar
                }

                const valor_venda = getValue(`#valor_venda_${loja}_${mes_ref}`);
                var perc_ads = (valor / valor_venda) * 100;



                $("#perc_ads_" + loja + "_" + mes_ref).html(perc_ads.toFixed(2) + "%");

                loadingDeTelaCurto()
                var array_lojas = [203656717, 204950867, 204074891, 203718490, 203780244, 204916439];
                array_lojas.forEach(element => {

                    /*                 $(`#lucro_bruto_${element}_${mes_ref}`).text(`R$ 0`);
                                    $(`#perc_lucro_bruto_${element}_${mes_ref}`).text(`0`);
                    
                                    $("#taxa_fixa_" + element + "_" + mes_ref).html("R$ 0");
                                    $("#perc_taxa_fixa_" + element + "_" + mes_ref).html("0");
                                    $("#custo_produto_" + element + "_" + mes_ref).html("R$ 0");
                                    $("#qtd_pedidos_" + element + "_" + mes_ref).html("0");
                                    $("#desconto_loja_" + element + "_" + mes_ref).html("R$ 0");
                                    $("#valor_venda_" + element + "_" + mes_ref).html("R$ 0");
                                    $("#tickt_medio_" + element + "_" + mes_ref).html("R$ 0");
                                    $("#total_mkt_" + element + "_" + mes_ref).html("R$ 0");
                                    $("#perc_custo_" + element + "_" + mes_ref).html("0");
                                    $("#frete_vendedor_" + element + "_" + mes_ref).html("R$ 0");
                                    $("#perc_custo_frete_" + element + "_" + mes_ref).html("0");
                                    $("#valor_ads_" + element + "_div_" + mes_ref).html("R$ 0");
                                    $("#perc_ads_" + element + "_" + mes_ref).html("0"); */

                    calculaValorLucro(element, mes_ref)

                });
            }
        })

        var clickedId = null; // Variável para armazenar o ID

        // Detecta o clique no elemento que abre a modal
        $('[data-toggle="modal"]').on('click', function () {
            clickedId = $(this).attr('id'); // Obtém o ID do elemento clicado
            $("#tipo").val(clickedId);
        });

        /* // Evento que dispara quando a modal é exibida
        $('.bd-example-modal-lg').on('show.bs.modal', function () {
            if (clickedId) {
                
                // Você pode realizar outras ações aqui
            }
        }); */


        $('.atualizar_infos').click(function () {
            //loadingDeTelaCurto()
            var mes_ref = $("#mes").val();

            $(`#total_pedidos_${mes_ref}`).text(``);
            $(`#custo_total_${mes_ref}`).text(`R$ 0`);
            $(`#desconto_loja_${mes_ref}`).text(`R$ 0`);
            $(`#valor_venda_${mes_ref}`).text(`R$ 0`);
            $(`#ticket_medio_${mes_ref}`).text(`R$ 0`);
            $(`#taxa_marketplace_${mes_ref}`).text(`R$ 0`);
            $(`#frete_vendedor_${mes_ref}`).text(`R$ 0`);
            $(`#ads_${mes_ref}`).text(`R$ 0`);
            $(`#taxa_fixa_${mes_ref}`).text(`R$ 0`);
            $(`#lucro_bruto_${mes_ref}`).text(`R$ 0`);
            $(`#percentual_custo_frete_${mes_ref}`).text(`0`);
            $(`#percentual_lucro_bruto_${mes_ref}`).text(`0`);
            $(`#percentual_custo_${mes_ref}`).text(`0`);


            var array_lojas = [203656717, 204950867, 204074891, 203718490, 203780244, 204916439];

            array_lojas.forEach(element => {

                /* $(`#lucro_bruto_${element}_${mes_ref}`).text(`R$ 0`);
                $(`#perc_lucro_bruto_${element}_${mes_ref}`).text(`0`); */


                /*                 $("#taxa_fixa_" + element + "_" + mes_ref).html("R$ 0");
                                $("#perc_taxa_fixa_" + element + "_" + mes_ref).html("0");
                                $("#custo_produto_" + element + "_" + mes_ref).html("R$ 0");
                                $("#qtd_pedidos_" + element + "_" + mes_ref).html("0");
                                $("#desconto_loja_" + element + "_" + mes_ref).html("R$ 0");
                                $("#valor_venda_" + element + "_" + mes_ref).html("R$ 0");
                                $("#tickt_medio_" + element + "_" + mes_ref).html("R$ 0");
                                $("#total_mkt_" + element + "_" + mes_ref).html("R$ 0");
                                $("#perc_custo_" + element + "_" + mes_ref).html("0");
                                $("#frete_vendedor_" + element + "_" + mes_ref).html("R$ 0");
                                $("#perc_custo_frete_" + element + "_" + mes_ref).html("0");
                                $("#valor_ads_" + element + "_div_" + mes_ref).html("R$ 0");
                                $("#perc_ads_" + element + "_" + mes_ref).html("0"); */

                calculaValorLucro(element, mes_ref)

            });
        })


        $('.cadastrar_tabela_dados_dre').click(function () {

            var identificacao = $("#identificacao").val();
            var valor_dado = $("#valor_dado").val();
            var tipo = $("#tipo_conferencia").val();
            var mes = $("#mes").val();
            var filtro_ano = $("#filtro_ano").val();
            var id_dado_dre = $("#id_dado_dre").val();

            if (filtro_ano != "") {


                $.ajax({
                    type: "POST",
                    url: base_url + '/Integracao/cadastrarDadoDRE',
                    data: {
                        'valor_dado': valor_dado,
                        'identificacao': identificacao,
                        'tipo': tipo,
                        'mes': mes,
                        'filtro_ano': filtro_ano,
                        'id_dado_dre': id_dado_dre
                    },
                    dataType: 'json',
                    success: function (data) {
                        //loadingDeTelaCurto()
                        var mes_ref = $("#mes").val();

                        $(`#total_pedidos_${mes_ref}`).text(``);
                        $(`#custo_total_${mes_ref}`).text(`R$ 0`);
                        $(`#desconto_loja_${mes_ref}`).text(`R$ 0`);
                        $(`#valor_venda_${mes_ref}`).text(`R$ 0`);
                        $(`#ticket_medio_${mes_ref}`).text(`R$ 0`);
                        $(`#taxa_marketplace_${mes_ref}`).text(`R$ 0`);
                        $(`#frete_vendedor_${mes_ref}`).text(`R$ 0`);
                        $(`#ads_${mes_ref}`).text(`R$ 0`);
                        $(`#taxa_fixa_${mes_ref}`).text(`R$ 0`);
                        $(`#lucro_bruto_${mes_ref}`).text(`R$ 0`);
                        $(`#percentual_custo_frete_${mes_ref}`).text(`0`);
                        $(`#percentual_lucro_bruto_${mes_ref}`).text(`0`);
                        $(`#percentual_custo_${mes_ref}`).text(`0`);


                        var array_lojas = [203656717, 204950867, 204074891, 203718490, 203780244, 204916439];

                        array_lojas.forEach(element => {

                            /* $(`#lucro_bruto_${element}_${mes_ref}`).text(`R$ 0`);
                            $(`#perc_lucro_bruto_${element}_${mes_ref}`).text(`0`); */


                            /*                 $("#taxa_fixa_" + element + "_" + mes_ref).html("R$ 0");
                                            $("#perc_taxa_fixa_" + element + "_" + mes_ref).html("0");
                                            $("#custo_produto_" + element + "_" + mes_ref).html("R$ 0");
                                            $("#qtd_pedidos_" + element + "_" + mes_ref).html("0");
                                            $("#desconto_loja_" + element + "_" + mes_ref).html("R$ 0");
                                            $("#valor_venda_" + element + "_" + mes_ref).html("R$ 0");
                                            $("#tickt_medio_" + element + "_" + mes_ref).html("R$ 0");
                                            $("#total_mkt_" + element + "_" + mes_ref).html("R$ 0");
                                            $("#perc_custo_" + element + "_" + mes_ref).html("0");
                                            $("#frete_vendedor_" + element + "_" + mes_ref).html("R$ 0");
                                            $("#perc_custo_frete_" + element + "_" + mes_ref).html("0");
                                            $("#valor_ads_" + element + "_div_" + mes_ref).html("R$ 0");
                                            $("#perc_ads_" + element + "_" + mes_ref).html("0"); */

                            calculaValorLucro(element, mes_ref)

                        });
                        if (data.msg == "sucesso") {
                            Swal.fire({
                                title: 'Sucesso',
                                text: "Dados cadastrados com sucesso! utilize o botão de atulizar para visualizar!",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK!'
                            }).then((result) => {
                                $("#identificacao").val("");
                                $("#valor_dado").val("");


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

        });

        $("#codigo_fornecedor").on('blur', function () {
            var codigo_fornecedor = $("#codigo_fornecedor").val();
            if (codigo_fornecedor != "") {
                $.ajax({
                    type: "POST",
                    url: base_url + '/Integracao/getSkuFornecedor',
                    data: {
                        'codigo_fornecedor': codigo_fornecedor
                    },
                    dataType: 'json',
                    success: async function (data) {
                        var dados = []
                        for (let i = 0; i < data.length; i++) {
                            const produto = data[i];
                            dados.push(produto.codigo)
                        }

                        if (dados.length > 1) {
                            const { value: valor } = await Swal.fire({
                                title: "Escolha um SKU",
                                input: "select",
                                inputOptions: dados,
                                inputPlaceholder: "Escolha o sku",
                            });
                            if (valor) {
                                var sku_escolhido = dados[valor]
                                $("#sku").val(sku_escolhido)
                            }
                        } else {
                            var sku_escolhido = dados[0]
                            $("#sku").val(sku_escolhido)
                        }

                    }
                });
            }
        })

        $(document).ready(function () {
            if ($('#sku').length) {
                $('#sku').on('keypress', function (e) {
                    if (e.key === 'Enter') {
                        if ($('#pesquisar_produto').length) {
                            $('#pesquisar_produto').trigger("click");
                        } else {
                            console.error("Elemento #pesquisar_produto não encontrado.");
                        }
                    }
                });
            }
        });



        $('#pesquisar_produto').click(function () {

            var sku = $("#sku").val();
            var codigo_fornecedor = $("#codigo_fornecedor").val();


            $.ajax({
                type: "POST",
                url: base_url + '/Integracao/getProduto',
                data: {
                    'sku': sku,
                    'codigo_fornecedor': codigo_fornecedor
                },
                dataType: 'json',
                success: function (data) {
                    
                    if(data.resposta != "erro"){
                        $("#custo_produto").val("");
                        //$("#gnre").val("");
                        //$("#embalagem").val("");
                        //$("#coleta_full").val("");
                        //$("#outros").val("");
                        $("#custo_total").html("");
    
                        var produtoId = data.id
                        $("#produtoId").val(produtoId);
                        var custo_antigo = data.precoCusto
                        var codigo = data.codigo
                        var altura = data.altura
                        var largura = data.largura
                        var profundidade = data.profundidade
                        var peso = data.peso
                        var descricao = data.nome
                        var peso_cubico = (largura * altura * profundidade) / 6000;
    
    
    
                        $("#descricao_produto").val(descricao);
                        $("#codigo_fornecedor").val(data.codigo_fornecedor);
    
                        $("#peso_produto").val(peso);
                        $("#peso_produto_div").html(peso + " Kg");
    
                        $("#largura_produto").val(largura);
                        $("#largura_produto_div").html(largura + " cm");
    
                        $("#altura_produto").val(altura);
                        $("#altura_produto_div").html(altura + " cm");
    
                        $("#profundidade_produto").val(profundidade);
                        $("#profundidade_produto_div").html(profundidade + " cm");
    
                        //$("#peso_cubico_produto").val(peso_cubico);
                        $("#peso_cubico_produto_div").html(peso_cubico.toFixed(2) + " Kg");
    
                        $("#custo_produto_div").html("R$ " + custo_antigo);
                        $("#custo_produto").val("R$ " + custo_antigo);
    
                        $("#custo_antigo").html("R$ " + custo_antigo);
    
                        $.ajax({
                            type: "POST",
                            url: base_url + '/Integracao/getProdutoMarketplace',
                            data: {
                                'produtoId': produtoId
                            },
                            dataType: 'json',
                            success: function (data) {
                                for (let i = 0; i < data.length; i++) {
    
                                    const produto = data[i];
                                    $("#valor_antigo_" + produto.loja_id).html("R$ " + produto.preco);
                                }
    
                            }
                        });
    
                        $.ajax({
                            type: "POST",
                            url: base_url + '/Integracao/gteEmbalagemProduto',
                            data: {
                                'altura': altura,
                                'largura': largura,
                                'profundidade': profundidade
                            },
                            dataType: 'json',
                            success: function (data) {
                                if (data != undefined) {
                                    $("#embalagem").val(data.valor)
                                    $("#embalagem_div").val(data.valor).trigger("click")
                                }
    
                            }
                        });
    
                        // Agora começa os calculos de Mkt
                    }else{
                        $("#sku").val("");
                        Swal.fire({
                            title: 'Atenção',
                            text: "Produto não encontrado",
                            icon: 'warning',
                            confirmButtonColor: '#3085d6',
                        }).then((result) => {
                            Swal.close()
                        })
                        
                    }

                    


                }
            });
        });

        $(".203656717_icone").hide()
        $(".203718490_icone").hide()
        $(".203780244_icone").hide()
        $(".204916439_icone").hide()
        $(".p1_icone").hide()
        $(".p2_icone").hide()

        function parseCurrency(value) {
            // Remove "R$" e converte para float, ou retorna 0 se o valor for inválido
            var numericValue = parseFloat(value.replace("R$ ", "").replace(",", "."));
            return isNaN(numericValue) ? 0 : numericValue;
        }


        $('#calcular_precificao').click(function () {



            var custo_produto_div = parseCurrency($("#custo_produto").val());
            var gnre = parseCurrency($("#gnre").val());
            var embalagem = parseCurrency($("#embalagem").val());
            var coleta_full = parseCurrency($("#coleta_full").val());
            var outros = parseCurrency($("#outros").val());



            var custo_total = custo_produto_div + gnre + embalagem + coleta_full + outros;

            let mensagensErro = []; // Array para acumular erros

            $("#custo_total").html("R$ " + custo_total.toFixed(2));

            $.ajax({
                type: "POST",
                url: base_url + '/Integracao/getDadosML',
                data: {
                },
                dataType: 'json',
                success: function (data) {

                    var taxa_comissao = data.comissao_marketplace_ml;
                    taxa_comissao = (100 - taxa_comissao) / 100;
                    var taxa_original = data.comissao_marketplace_ml / 100;

                    var taxa_imposto = data.imposto_ml;
                    taxa_imposto = (100 - taxa_imposto) / 100;
                    var imposto_original = data.imposto_ml / 100;

                    var taxa_lucro = data.lucro_ml;
                    var lucro_original = data.lucro_ml;
                    taxa_lucro = (100 - taxa_lucro) / 100;

                    $("#lucro_203656717_hidden").val(taxa_lucro)

                    var taxa_promo = data.campanha_desconto_ml;
                    taxa_promo = (100 - taxa_promo) / 100;

                    $("#taxa_203656717_hidden").val(taxa_promo)

                    var taxa_total = data.total_taxas_ml;
                    taxa_total = (100 - taxa_total) / 100;

                    var taxa_fixa = parseFloat(data.taxa_fixa_ml);

                    var valor_frete = 0;
                    var valor_venda = 0;
                    var valor_venda_promo = 0;
                    $.ajax({
                        type: "POST",
                        url: base_url + '/Integracao/getDadosFrete',
                        data: {
                            'id': data.id,
                            'tipo': 'ml'
                        },
                        dataType: 'json',
                        success: function (data) {

                            var peso_cubico = parseFloat($("#peso_cubico_produto_div").html().replace(" Kg", ""));
                            var peso_produto = parseFloat($("#peso_produto").val());

                            var peso_final = 0;
                            if (peso_cubico >= peso_produto) {
                                peso_final = peso_cubico;
                            } else {
                                peso_final = peso_produto;
                            }

                            

                            var custo_total_novo = custo_total.toFixed(2)
                            valor_venda = parseFloat(custo_total_novo) + taxa_fixa;
                            valor_venda = parseFloat(valor_venda) / taxa_total;
                            

                            let valor_frete = null; // Variável para armazenar o valor do frete correspondente

                            if (data.length > 0) {
                                for (let i = 0; i < data.length; i++) {
                                    const frete = data[i];

                                    // Convertendo os intervalos para números
                                    const de = parseFloat(frete.de);
                                    const ate = parseFloat(frete.ate);

                                    const de_valor = parseFloat(frete.valor_inicial);
                                    var ate_valor = parseFloat(frete.valor_final);

                                    //verifica se não tem ate_valor, se ntiver coloca 1milhão
                                    if (!ate_valor || ate_valor == 0 || ate_valor == null) {
                                        ate_valor = 1000000;
                                    }

                                    if (valor_venda >= de_valor && valor_venda <= ate_valor) {

                                        // Verificando se o peso_final está no intervalo
                                        if (peso_final >= de && peso_final <= ate) {
                                            valor_frete = frete.valor;
                                            $("#frete_203656717").html("R$ " + valor_frete)
                                            $("#frete_203656717_div").html("R$ " + valor_frete)
                                            break; // Interrompe o loop assim que encontrar o intervalo
                                        } else {
                                            $("#frete_203656717").html("R$ 0")
                                            $("#frete_203656717_div").html("R$ 0")
                                        }
                                    } else {
                                        $("#frete_203656717").html("R$ 0")
                                        $("#frete_203656717_div").html("R$ 0")
                                    }

                                    

                                    
                                }
                            } else {
                                $("#frete_203656717").html("R$ 0")
                                $("#frete_203656717_div").html("R$ 0")
                            }       


                            if (valor_frete === null || data.length < 0) {
                                mensagensErro.push("Nenhum frete foi encontrado nas configurações do Mercado Livre para esse peso");
                            }

                            if (valor_venda >= 79) {    
                                $("#taxa_marketplace_original_203656717_hidden").val(taxa_total);
                                var custo_total_novo = custo_total.toFixed(2)
                                console.log(custo_total_novo)
                                valor_venda = parseFloat(custo_total_novo) + parseFloat((valor_frete ?? "0").replace(",", "."));
                                valor_venda = valor_venda / taxa_total;
                                taxa_fixa = 0;
                            }

                            var taxa_origina_percentual = taxa_original;
                            var imposto_origina_percentual = imposto_original;
                            var lucro_origina_percentual = lucro_original / 100;

                            var valor_imposto_individual = valor_venda * imposto_origina_percentual;
                            var valor_taxa_individual = valor_venda * taxa_origina_percentual;
                            var valor_lucro_individual = valor_venda * lucro_origina_percentual;

                            $("#taxa_original_203656717_hidden").val(taxa_origina_percentual);
                            $("#lucro_original_203656717_hidden").val(lucro_origina_percentual);
                            $("#imposto_original_203656717_hidden").val(imposto_origina_percentual);
                            $("#frete_original_203656717_hidden").val(valor_frete);
                            $("#taxa_Fixa_original_203656717_hidden").val(taxa_fixa);


                            const resultado = `
                            <span style="text-align: center"> Resumo de valores </span>
                            </br></br>
                            Valor venda: ${valor_venda.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Frete: ${valor_frete != null ? "R$ " + valor_frete.toLocaleString("pt-BR", { style: "currency", currency: "BRL" }) : "R$ 0,00"}</br>
                            Taxa: ${valor_taxa_individual.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Imposto: ${valor_imposto_individual.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Custo total: ${custo_total.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Taxa fixa: ${taxa_fixa.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}`;

                            var $elemento = $("#div_principal_valor_venda_203656717");

                            // Remover o title padrão para evitar conflito
                            $elemento.removeAttr("title");

                            // Exibir tooltip ao passar o mouse
                            $elemento.hover(function (event) {
                                $(".custom-tooltip").html(resultado).css({
                                    top: event.pageY + 10 + "px",
                                    left: event.pageX + 10 + "px"
                                }).fadeIn(200);
                            }, function () {
                                $(".custom-tooltip").fadeOut(200);
                            });

                            // Atualizar posição ao mover o mouse
                            $elemento.mousemove(function (event) {
                                $(".custom-tooltip").css({
                                    top: event.pageY + 10 + "px",
                                    left: event.pageX + 10 + "px"
                                });
                            });


                            var valor_venda_promo = valor_venda / taxa_promo;

                            if (valor_venda < 79 && valor_venda_promo > 79) {
                                $(".203656717_icone").show()
                            } else {
                                $(".203656717_icone").hide()
                            }

                            $("#valor_venda_203656717_hidden").val("R$ " + valor_venda.toFixed(2))
                            $("#valor_venda_203656717").val("R$ " + valor_venda.toFixed(2))
                            $("#valor_venda_203656717_div").html("R$ " + valor_venda.toFixed(2))

                            valor_frete = parseFloat((valor_frete ?? "0").replace(",", "."));
                            $("#frete_203656717_hidden").val("R$ " + valor_frete.toFixed(2))
                            $("#frete_203656717").val("R$ " + valor_frete.toFixed(2))
                            $("#frete_203656717_div").html("R$ " + valor_frete.toFixed(2))

                            $("#valor_campanha_203656717").val("R$ " + valor_venda_promo.toFixed(2))
                            $("#valor_campanha_203656717_div").html("R$ " + valor_venda_promo.toFixed(2))
                            

                            //Valores para descontar
                            //var imposto  = valor_venda.toFixed(2)*(data.imposto_ml/100)
                            //var comissao  = valor_venda.toFixed(2)*(data.comissao_marketplace_ml/100)
                            var lucro = valor_venda.toFixed(2) * (lucro_original / 100)
                            $("#mc_v_203656717_hidden").val("R$ " + lucro.toFixed(2))
                            $("#mc_v_203656717").val("R$ " + lucro.toFixed(2))
                            $("#mc_v_203656717_div").html("R$ " + lucro.toFixed(2))

                            var lucro_perc = (lucro / valor_venda) * 100;
                            $("#mc_p_203656717_hidden").val(Math.round(lucro_perc.toFixed(2)) + "%")
                            $("#mc_p_203656717").val(Math.round(lucro_perc.toFixed(2)) + "%")
                            $("#mc_p_203656717_div").html(Math.round(lucro_perc.toFixed(2)) + "%")
                        }
                    });





                }
            });
            


            $.ajax({
                type: "POST",
                url: base_url + '/Integracao/getDadosP1',
                data: {
                },
                dataType: 'json',
                success: function (data) {
                    var taxa_comissao = data.comissao_marketplace_p1;
                    taxa_comissao = (100 - taxa_comissao) / 100;
                    var taxa_original = data.comissao_marketplace_p1 / 100;

                    var taxa_imposto = data.imposto_p1;
                    taxa_imposto = (100 - taxa_imposto) / 100;
                    var imposto_original = data.imposto_p1 / 100;

                    var taxa_lucro = data.lucro_p1;
                    var lucro_original = data.lucro_p1;
                    taxa_lucro = (100 - taxa_lucro) / 100;

                    $("#lucro_p1_hidden").val(taxa_lucro)

                    var taxa_promo = data.campanha_desconto_p1;
                    taxa_promo = (100 - taxa_promo) / 100;

                    $("#taxa_p1_hidden").val(taxa_promo)

                    var taxa_total = data.total_taxas_p1;
                    taxa_total = (100 - taxa_total) / 100;

                    var taxa_fixa = parseFloat(data.taxa_fixa_p1);

                    var valor_frete = 0;
                    var valor_venda = 0;
                    var valor_venda_promo = 0;
                    $.ajax({
                        type: "POST",
                        url: base_url + '/Integracao/getDadosFrete',
                        data: {
                            'id': data.id,
                            'tipo': 'ml'
                        },
                        dataType: 'json',
                        success: function (data) {

                            var peso_cubico = parseFloat($("#peso_cubico_produto_div").html().replace(" Kg", ""));
                            var peso_produto = parseFloat($("#peso_produto").val());

                            var peso_final = 0;
                            if (peso_cubico >= peso_produto) {
                                peso_final = peso_cubico;
                            } else {
                                peso_final = peso_produto;
                            }

                            

                            var custo_total_novo = custo_total.toFixed(2)
                            valor_venda = parseFloat(custo_total_novo) + taxa_fixa;
                            valor_venda = parseFloat(valor_venda) / taxa_total;
                            

                            let valor_frete = null; // Variável para armazenar o valor do frete correspondente

                            if (data.length > 0) {
                                for (let i = 0; i < data.length; i++) {
                                    const frete = data[i];

                                    // Convertendo os intervalos para números
                                    const de = parseFloat(frete.de);
                                    const ate = parseFloat(frete.ate);

                                    const de_valor = parseFloat(frete.valor_inicial);
                                    var ate_valor = parseFloat(frete.valor_final);

                                    //verifica se não tem ate_valor, se ntiver coloca 1milhão
                                    if (!ate_valor || ate_valor == 0 || ate_valor == null) {
                                        ate_valor = 1000000;
                                    }

                                    if (valor_venda >= de_valor && valor_venda <= ate_valor) {

                                        // Verificando se o peso_final está no intervalo
                                        if (peso_final >= de && peso_final <= ate) {
                                            valor_frete = frete.valor;
                                            $("#frete_p1").html("R$ " + valor_frete)
                                            $("#frete_p1_div").html("R$ " + valor_frete)
                                            break; // Interrompe o loop assim que encontrar o intervalo
                                        } else {
                                            $("#frete_p1").html("R$ 0")
                                            $("#frete_p1_div").html("R$ 0")
                                        }
                                    } else {
                                        $("#frete_p1").html("R$ 0")
                                        $("#frete_p1_div").html("R$ 0")
                                    }

                                    

                                    
                                }
                            } else {
                                $("#frete_p1").html("R$ 0")
                                $("#frete_p1_div").html("R$ 0")
                            }       


                            if (valor_frete === null || data.length < 0) {
                                mensagensErro.push("Nenhum frete foi encontrado nas configurações do Mercado Livre para esse peso");
                            }

                            if (valor_venda >= 79) {    
                                $("#taxa_marketplace_original_p1_hidden").val(taxa_total);
                                var custo_total_novo = custo_total.toFixed(2)
                                console.log(custo_total_novo)
                                valor_venda = parseFloat(custo_total_novo) + parseFloat((valor_frete ?? "0").replace(",", "."));
                                valor_venda = valor_venda / taxa_total;
                                taxa_fixa = 0;
                            }

                            var taxa_origina_percentual = taxa_original;
                            var imposto_origina_percentual = imposto_original;
                            var lucro_origina_percentual = lucro_original / 100;

                            var valor_imposto_individual = valor_venda * imposto_origina_percentual;
                            var valor_taxa_individual = valor_venda * taxa_origina_percentual;
                            var valor_lucro_individual = valor_venda * lucro_origina_percentual;

                            $("#taxa_original_p1_hidden").val(taxa_origina_percentual);
                            $("#lucro_original_p1_hidden").val(lucro_origina_percentual);
                            $("#imposto_original_p1_hidden").val(imposto_origina_percentual);
                            $("#frete_original_p1_hidden").val(valor_frete);
                            $("#taxa_Fixa_original_p1_hidden").val(taxa_fixa);


                            const resultado = `
                            <span style="text-align: center"> Resumo de valores </span>
                            </br></br>
                            Valor venda: ${valor_venda.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Frete: ${valor_frete != null ? "R$ " + valor_frete.toLocaleString("pt-BR", { style: "currency", currency: "BRL" }) : "R$ 0,00"}</br>
                            Taxa: ${valor_taxa_individual.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Imposto: ${valor_imposto_individual.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Custo total: ${custo_total.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Taxa fixa: ${taxa_fixa.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}`;

                            var $elemento = $("#div_principal_valor_venda_p1");

                            // Remover o title padrão para evitar conflito
                            $elemento.removeAttr("title");

                            // Exibir tooltip ao passar o mouse
                            $elemento.hover(function (event) {
                                $(".custom-tooltip").html(resultado).css({
                                    top: event.pageY + 10 + "px",
                                    left: event.pageX + 10 + "px"
                                }).fadeIn(200);
                            }, function () {
                                $(".custom-tooltip").fadeOut(200);
                            });

                            // Atualizar posição ao mover o mouse
                            $elemento.mousemove(function (event) {
                                $(".custom-tooltip").css({
                                    top: event.pageY + 10 + "px",
                                    left: event.pageX + 10 + "px"
                                });
                            });


                            var valor_venda_promo = valor_venda / taxa_promo;

                            if (valor_venda < 79 && valor_venda_promo > 79) {
                                $(".p1_icone").show()
                            } else {
                                $(".p1_icone").hide()
                            }

                            $("#valor_venda_p1_hidden").val("R$ " + valor_venda.toFixed(2))
                            $("#valor_venda_p1").val("R$ " + valor_venda.toFixed(2))
                            $("#valor_venda_p1_div").html("R$ " + valor_venda.toFixed(2))

                            valor_frete = parseFloat((valor_frete ?? "0").replace(",", "."));
                            $("#frete_p1_hidden").val("R$ " + valor_frete.toFixed(2))
                            $("#frete_p1").val("R$ " + valor_frete.toFixed(2))
                            $("#frete_p1_div").html("R$ " + valor_frete.toFixed(2))

                            $("#valor_campanha_p1").val("R$ " + valor_venda_promo.toFixed(2))
                            $("#valor_campanha_p1_div").html("R$ " + valor_venda_promo.toFixed(2))
                            

                            //Valores para descontar
                            //var imposto  = valor_venda.toFixed(2)*(data.imposto_ml/100)
                            //var comissao  = valor_venda.toFixed(2)*(data.comissao_marketplace_ml/100)
                            var lucro = valor_venda.toFixed(2) * (lucro_original / 100)
                            $("#mc_v_p1_hidden").val("R$ " + lucro.toFixed(2))
                            $("#mc_v_p1").val("R$ " + lucro.toFixed(2))
                            $("#mc_v_p1_div").html("R$ " + lucro.toFixed(2))

                            var lucro_perc = (lucro / valor_venda) * 100;
                            $("#mc_p_p1_hidden").val(Math.round(lucro_perc.toFixed(2)) + "%")
                            $("#mc_p_p1").val(Math.round(lucro_perc.toFixed(2)) + "%")
                            $("#mc_p_p1_div").html(Math.round(lucro_perc.toFixed(2)) + "%")
                        }
                    });



                }
            });

            $.ajax({
                type: "POST",
                url: base_url + '/Integracao/getDadosSP',
                data: {
                },
                dataType: 'json',
                success: function (data) {
                    var taxa_comissao = data.comissao_marketplace_sp;
                    taxa_comissao = (100 - taxa_comissao) / 100;
                    var taxa_original = data.comissao_marketplace_sp / 100;

                    var taxa_imposto = data.imposto_sp;
                    taxa_imposto = (100 - taxa_imposto) / 100;
                    var imposto_original = data.imposto_sp / 100;

                    var taxa_lucro = data.lucro_sp;
                    var lucro_original = data.lucro_sp;
                    taxa_lucro = (100 - taxa_lucro) / 100;

                    $("#lucro_203718490_hidden").val(taxa_lucro)

                    var taxa_promo = data.campanha_desconto_sp;
                    taxa_promo = (100 - taxa_promo) / 100;

                    $("#taxa_203718490_hidden").val(taxa_promo)

                    var taxa_total = data.total_taxas_sp;
                    taxa_total = (100 - taxa_total) / 100;

                    var taxa_fixa = parseFloat(data.taxa_fixa_sp);

                    var valor_frete = 0;
                    var valor_venda = 0;
                    var valor_venda_promo = 0;
                    $.ajax({
                        type: "POST",
                        url: base_url + '/Integracao/getDadosFrete',
                        data: {
                            'id': data.id,
                            'tipo': 'sp'
                        },
                        dataType: 'json',
                        success: function (data) {

                            var peso_cubico = parseFloat($("#peso_cubico_produto_div").html().replace(" Kg", ""));
                            var peso_produto = parseFloat($("#peso_produto").val());

                            var peso_final = 0;
                            if (peso_cubico >= peso_produto) {
                                peso_final = peso_cubico;
                            } else {
                                peso_final = peso_produto;
                            }

                            let valor_frete = null; // Variável para armazenar o valor do frete correspondente

                            if (data.length > 0) {
                                for (let i = 0; i < data.length; i++) {
                                    const frete = data[i];

                                    // Convertendo os intervalos para números
                                    const de = parseFloat(frete.de);
                                    const ate = parseFloat(frete.ate);

                                    // Verificando se o peso_final está no intervalo
                                    if (peso_final >= de && peso_final <= ate) {
                                        valor_frete = frete.valor;
                                        $("#frete_203718490").html("R$ " + valor_frete)
                                        $("#frete_203718490_div").html("R$ " + valor_frete)

                                        break; // Interrompe o loop assim que encontrar o intervalo
                                    } else {
                                        $("#frete_203718490").html("R$ 0")
                                        $("#frete_203718490_div").html("R$ 0")
                                    }
                                }
                            } else {
                                $("#frete_203718490").html("R$ 0")
                                $("#frete_203718490_div").html("R$ 0")
                            }


                            if (valor_frete === null || data.length < 0) {
                                mensagensErro.push("Nenhum frete foi encontrado nas configurações da Shopee para esse peso");

                            }
                            var custo_total_novo = custo_total.toFixed(2)
                            valor_venda = parseFloat(custo_total_novo) + taxa_fixa;
                            valor_venda = parseFloat(valor_venda) / taxa_total;

                            if (valor_venda >= 79) {
                                var custo_total_novo = custo_total.toFixed(2)
                                valor_venda = parseFloat(custo_total_novo) + parseFloat((valor_frete ?? "0").replace(",", "."));
                                valor_venda = valor_venda / taxa_total;
                                taxa_fixa = 0;
                            }
                            var taxa_origina_percentual = taxa_original;
                            var imposto_origina_percentual = imposto_original;
                            var lucro_origina_percentual = lucro_original / 100;

                            var valor_imposto_individual = valor_venda * imposto_origina_percentual;
                            var valor_taxa_individual = valor_venda * taxa_origina_percentual;
                            var valor_lucro_individual = valor_venda * lucro_origina_percentual;

                            $("#taxa_original_203718490_hidden").val(taxa_origina_percentual);
                            $("#lucro_original_203718490_hidden").val(lucro_origina_percentual);
                            $("#imposto_original_203718490_hidden").val(imposto_origina_percentual);
                            $("#frete_original_203718490_hidden").val(valor_frete);
                            $("#taxa_Fixa_original_203718490_hidden").val(taxa_fixa);

                            const resultado = `
                            <span style="text-align: center"> Resumo de valores </span>
                            </br></br>
                            Valor venda: ${valor_venda.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Frete: ${valor_frete != null ? "R$ " + valor_frete.toLocaleString("pt-BR", { style: "currency", currency: "BRL" }) : "R$ 0,00"}</br>
                            Taxa: ${valor_taxa_individual.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Imposto: ${valor_imposto_individual.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Custo total: ${custo_total.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Taxa fixa: ${taxa_fixa.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}`;

                            var $elemento = $("#div_principal_valor_venda_203718490");

                            // Remover o title padrão para evitar conflito
                            $elemento.removeAttr("title");

                            // Exibir tooltip ao passar o mouse
                            $elemento.hover(function (event) {
                                $(".custom-tooltip").html(resultado).css({
                                    top: event.pageY + 10 + "px",
                                    left: event.pageX + 10 + "px"
                                }).fadeIn(200);
                            }, function () {
                                $(".custom-tooltip").fadeOut(200);
                            });

                            // Atualizar posição ao mover o mouse
                            $elemento.mousemove(function (event) {
                                $(".custom-tooltip").css({
                                    top: event.pageY + 10 + "px",
                                    left: event.pageX + 10 + "px"
                                });
                            });

                            var valor_venda_promo = valor_venda / taxa_promo;

                            if (valor_venda < 79 && valor_venda_promo > 79) {
                                $(".203718490_icone").show()
                            } else {
                                $(".203718490_icone").hide()
                            }

                            $("#valor_venda_203718490_hidden").val("R$ " + valor_venda.toFixed(2))
                            $("#valor_venda_203718490").val("R$ " + valor_venda.toFixed(2))
                            $("#valor_venda_203718490_div").html("R$ " + valor_venda.toFixed(2))

                            $("#valor_campanha_203718490").val("R$ " + valor_venda_promo.toFixed(2))
                            $("#valor_campanha_203718490_div").html("R$ " + valor_venda_promo.toFixed(2))

                            //Valores para descontar
                            //var imposto  = valor_venda.toFixed(2)*(data.imposto_sp/100)
                            //var comissao  = valor_venda.toFixed(2)*(data.comissao_marketplace_sp/100)
                            var lucro = valor_venda.toFixed(2) * (lucro_original / 100)
                            $("#mc_v_203718490_hidden").val("R$ " + lucro.toFixed(2))
                            $("#mc_v_203718490").val("R$ " + lucro.toFixed(2))
                            $("#mc_v_203718490_div").html("R$ " + lucro.toFixed(2))

                            var lucro_perc = (lucro / valor_venda) * 100;
                            $("#mc_p_203718490_hidden").val(Math.round(lucro_perc.toFixed(2)) + "%")
                            $("#mc_p_203718490").val(Math.round(lucro_perc.toFixed(2)) + "%")
                            $("#mc_p_203718490_div").html(Math.round(lucro_perc.toFixed(2)) + "%")
                        }
                    });



                }
            });

            $.ajax({
                type: "POST",
                url: base_url + '/Integracao/getDadosMG',
                data: {
                },
                dataType: 'json',
                success: function (data) {
                    var taxa_comissao = data.comissao_marketplace_mg;
                    taxa_comissao = (100 - taxa_comissao) / 100;
                    var taxa_original = data.comissao_marketplace_mg / 100;

                    var taxa_imposto = data.imposto_mg;
                    taxa_imposto = (100 - taxa_imposto) / 100;
                    var imposto_original = data.imposto_mg / 100;

                    var taxa_lucro = data.lucro_mg;
                    var lucro_original = data.lucro_mg;
                    taxa_lucro = (100 - taxa_lucro) / 100;

                    $("#lucro_203780244_hidden").val(taxa_lucro)

                    var taxa_promo = data.campanha_desconto_mg;
                    taxa_promo = (100 - taxa_promo) / 100;

                    $("#taxa_203780244_hidden").val(taxa_promo)

                    var taxa_total = data.total_taxas_mg;
                    taxa_total = (100 - taxa_total) / 100;

                    var taxa_fixa = parseFloat(data.taxa_fixa_mg);

                    var valor_frete = 0;
                    var valor_venda = 0;
                    var valor_venda_promo = 0;
                    $.ajax({
                        type: "POST",
                        url: base_url + '/Integracao/getDadosFrete',
                        data: {
                            'id': data.id,
                            'tipo': 'mg'
                        },
                        dataType: 'json',
                        success: function (data) {

                            var peso_cubico = parseFloat($("#peso_cubico_produto_div").html().replace(" Kg", ""));
                            var peso_produto = parseFloat($("#peso_produto").val());

                            var peso_final = 0;
                            if (peso_cubico >= peso_produto) {
                                peso_final = peso_cubico;
                            } else {
                                peso_final = peso_produto;
                            }

                            let valor_frete = null; // Variável para armazenar o valor do frete correspondente

                            if (data.length > 0) {
                                for (let i = 0; i < data.length; i++) {
                                    const frete = data[i];

                                    // Convertendo os intervalos para números
                                    const de = parseFloat(frete.de);
                                    const ate = parseFloat(frete.ate);

                                    // Verificando se o peso_final está no intervalo
                                    if (peso_final >= de && peso_final <= ate) {
                                        valor_frete = frete.valor;
                                        $("#frete_203780244").html("R$ " + valor_frete)
                                        $("#frete_203780244_div").html("R$ " + valor_frete)

                                        break; // Interrompe o loop assim que encontrar o intervalo
                                    } else {
                                        $("#frete_203780244").html("R$ 0")
                                        $("#frete_203780244_div").html("R$ 0")
                                    }
                                }
                            } else {
                                $("#frete_203780244").html("R$ 0")
                                $("#frete_203780244_div").html("R$ 0")
                            }


                            if (valor_frete === null || data.length < 0) {
                                mensagensErro.push("Nenhum frete foi encontrado nas configurações do Magalu para esse peso");

                            }
                            var custo_total_novo = custo_total.toFixed(2)
                            valor_venda = parseFloat(custo_total_novo) + taxa_fixa;
                            valor_venda = parseFloat(valor_venda) / taxa_total;

                            if (valor_venda >= 79) {
                                var custo_total_novo = custo_total.toFixed(2)
                                valor_venda = parseFloat(custo_total_novo) + parseFloat((valor_frete ?? "0").replace(",", "."));
                                valor_venda = valor_venda / taxa_total;
                                taxa_fixa = 0;
                            }
                            var taxa_origina_percentual = taxa_original;
                            var imposto_origina_percentual = imposto_original;
                            var lucro_origina_percentual = lucro_original / 100;

                            var valor_imposto_individual = valor_venda * imposto_origina_percentual;
                            var valor_taxa_individual = valor_venda * taxa_origina_percentual;
                            var valor_lucro_individual = valor_venda * lucro_origina_percentual;

                            $("#taxa_original_203780244_hidden").val(taxa_origina_percentual);
                            $("#lucro_original_203780244_hidden").val(lucro_origina_percentual);
                            $("#imposto_original_203780244_hidden").val(imposto_origina_percentual);
                            $("#frete_original_203780244_hidden").val(valor_frete);
                            $("#taxa_Fixa_original_203780244_hidden").val(taxa_fixa);

                            const resultado = `
                            <span style="text-align: center"> Resumo de valores </span>
                            </br></br>
                            Valor venda: ${valor_venda.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Frete: ${valor_frete != null ? "R$ " + valor_frete.toLocaleString("pt-BR", { style: "currency", currency: "BRL" }) : "R$ 0,00"}</br>
                            Taxa: ${valor_taxa_individual.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Imposto: ${valor_imposto_individual.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Custo total: ${custo_total.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Taxa fixa: ${taxa_fixa.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}`;

                            var $elemento = $("#div_principal_valor_venda_203780244");

                            // Remover o title padrão para evitar conflito
                            $elemento.removeAttr("title");

                            // Exibir tooltip ao passar o mouse
                            $elemento.hover(function (event) {
                                $(".custom-tooltip").html(resultado).css({
                                    top: event.pageY + 10 + "px",
                                    left: event.pageX + 10 + "px"
                                }).fadeIn(200);
                            }, function () {
                                $(".custom-tooltip").fadeOut(200);
                            });

                            // Atualizar posição ao mover o mouse
                            $elemento.mousemove(function (event) {
                                $(".custom-tooltip").css({
                                    top: event.pageY + 10 + "px",
                                    left: event.pageX + 10 + "px"
                                });
                            });

                            var valor_venda_promo = valor_venda / taxa_promo;

                            if (valor_venda < 79 && valor_venda_promo > 79) {
                                $(".203780244_icone").show()
                            } else {
                                $(".203780244_icone").hide()
                            }

                            $("#valor_venda_203780244_hidden").val("R$ " + valor_venda.toFixed(2))
                            $("#valor_venda_203780244").val("R$ " + valor_venda.toFixed(2))
                            $("#valor_venda_203780244_div").html("R$ " + valor_venda.toFixed(2))

                            $("#valor_campanha_203780244").val("R$ " + valor_venda_promo.toFixed(2))
                            $("#valor_campanha_203780244_div").html("R$ " + valor_venda_promo.toFixed(2))

                            //Valores para descontar
                            //var imposto  = valor_venda.toFixed(2)*(data.imposto_mg/100)
                            //var comissao  = valor_venda.toFixed(2)*(data.comissao_marketplace_mg/100)
                            var lucro = valor_venda.toFixed(2) * (lucro_original / 100)
                            $("#mc_v_203780244_hidden").val("R$ " + lucro.toFixed(2))
                            $("#mc_v_203780244").val("R$ " + lucro.toFixed(2))
                            $("#mc_v_203780244_div").html("R$ " + lucro.toFixed(2))

                            var lucro_perc = (lucro / valor_venda) * 100;
                            $("#mc_p_203780244_hidden").val(Math.round(lucro_perc.toFixed(2)) + "%")
                            $("#mc_p_203780244").val(Math.round(lucro_perc.toFixed(2)) + "%")
                            $("#mc_p_203780244_div").html(Math.round(lucro_perc.toFixed(2)) + "%")
                        }
                    });






                }
            });

            $.ajax({
                type: "POST",
                url: base_url + '/Integracao/getDadosYM',
                data: {
                },
                dataType: 'json',
                success: function (data) {
                    var taxa_comissao = data.comissao_marketplace_ym;
                    taxa_comissao = (100 - taxa_comissao) / 100;
                    var taxa_original = data.comissao_marketplace_ym / 100;

                    var taxa_imposto = data.imposto_ym;
                    taxa_imposto = (100 - taxa_imposto) / 100;
                    var imposto_original = data.imposto_ym / 100;

                    var taxa_lucro = data.lucro_ym;
                    var lucro_original = data.lucro_ym;
                    taxa_lucro = (100 - taxa_lucro) / 100;

                    $("#lucro_204916439_hidden").val(taxa_lucro)

                    var taxa_promo = data.campanha_desconto_ym;
                    taxa_promo = (100 - taxa_promo) / 100;

                    $("#taxa_204916439_hidden").val(taxa_promo)

                    var taxa_total = data.total_taxas_ym;
                    taxa_total = (100 - taxa_total) / 100;

                    var taxa_fixa = parseFloat(data.taxa_fixa_ym);

                    var valor_frete = 0;
                    var valor_venda = 0;
                    var valor_venda_promo = 0;
                    $.ajax({
                        type: "POST",
                        url: base_url + '/Integracao/getDadosFrete',
                        data: {
                            'id': data.id,
                            'tipo': 'ym'
                        },
                        dataType: 'json',
                        success: function (data) {

                            var peso_cubico = parseFloat($("#peso_cubico_produto_div").html().replace(" Kg", ""));
                            var peso_produto = parseFloat($("#peso_produto").val());

                            var peso_final = 0;
                            if (peso_cubico >= peso_produto) {
                                peso_final = peso_cubico;
                            } else {
                                peso_final = peso_produto;
                            }

                            let valor_frete = null; // Variável para armazenar o valor do frete correspondente

                            if (data.length > 0) {
                                for (let i = 0; i < data.length; i++) {
                                    const frete = data[i];

                                    // Convertendo os intervalos para números
                                    const de = parseFloat(frete.de);
                                    const ate = parseFloat(frete.ate);

                                    // Verificando se o peso_final está no intervalo
                                    if (peso_final >= de && peso_final <= ate) {
                                        valor_frete = frete.valor;
                                        $("#frete_204916439").html("R$ " + valor_frete)
                                        $("#frete_204916439_div").html("R$ " + valor_frete)

                                        break; // Interrompe o loop assim que encontrar o intervalo
                                    } else {
                                        $("#frete_204916439").html("R$ 0")
                                        $("#frete_204916439_div").html("R$ 0")
                                    }
                                }
                            } else {
                                $("#frete_204916439").html("R$ 0")
                                $("#frete_204916439_div").html("R$ 0")
                            }


                            if (valor_frete === null || data.length < 0) {
                                mensagensErro.push("Nenhum frete foi encontrado nas configurações do Yampi para esse peso");

                            }
                            var custo_total_novo = custo_total.toFixed(2)
                            valor_venda = parseFloat(custo_total_novo) + taxa_fixa;
                            valor_venda = parseFloat(valor_venda) / taxa_total;

                            if (valor_venda >= 79) {
                                var custo_total_novo = custo_total.toFixed(2)
                                valor_venda = parseFloat(custo_total_novo) + parseFloat((valor_frete ?? "0").replace(",", "."));
                                valor_venda = valor_venda / taxa_total;
                                taxa_fixa = 0;
                            }
                            var taxa_origina_percentual = taxa_original;
                            var imposto_origina_percentual = imposto_original;
                            var lucro_origina_percentual = lucro_original / 100;

                            var valor_imposto_individual = valor_venda * imposto_origina_percentual;
                            var valor_taxa_individual = valor_venda * taxa_origina_percentual;
                            var valor_lucro_individual = valor_venda * lucro_origina_percentual;

                            $("#taxa_original_204916439_hidden").val(taxa_origina_percentual);
                            $("#lucro_original_204916439_hidden").val(lucro_origina_percentual);
                            $("#imposto_original_204916439_hidden").val(imposto_origina_percentual);
                            $("#frete_original_204916439_hidden").val(valor_frete);
                            $("#taxa_Fixa_original_204916439_hidden").val(taxa_fixa);

                            const resultado = `
                            <span style="text-align: center"> Resumo de valores </span>
                            </br></br>
                            Valor venda: ${valor_venda.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Frete: ${valor_frete != null ? "R$ " + valor_frete.toLocaleString("pt-BR", { style: "currency", currency: "BRL" }) : "R$ 0,00"}</br>
                            Taxa: ${valor_taxa_individual.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Imposto: ${valor_imposto_individual.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Custo total: ${custo_total.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Taxa fixa: ${taxa_fixa.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}`;

                            var $elemento = $("#div_principal_valor_venda_204916439");

                            // Remover o title padrão para evitar conflito
                            $elemento.removeAttr("title");

                            // Exibir tooltip ao passar o mouse
                            $elemento.hover(function (event) {
                                $(".custom-tooltip").html(resultado).css({
                                    top: event.pageY + 10 + "px",
                                    left: event.pageX + 10 + "px"
                                }).fadeIn(200);
                            }, function () {
                                $(".custom-tooltip").fadeOut(200);
                            });

                            // Atualizar posição ao mover o mouse
                            $elemento.mousemove(function (event) {
                                $(".custom-tooltip").css({
                                    top: event.pageY + 10 + "px",
                                    left: event.pageX + 10 + "px"
                                });
                            });

                            var valor_venda_promo = valor_venda / taxa_promo;

                            if (valor_venda < 79 && valor_venda_promo > 79) {
                                $(".204916439_icone").show()
                            } else {
                                $(".204916439_icone").hide()
                            }

                            $("#valor_venda_204916439_hidden").val("R$ " + valor_venda.toFixed(2))
                            $("#valor_venda_204916439").val("R$ " + valor_venda.toFixed(2))
                            $("#valor_venda_204916439_div").html("R$ " + valor_venda.toFixed(2))

                            $("#valor_campanha_204916439").val("R$ " + valor_venda_promo.toFixed(2))
                            $("#valor_campanha_204916439_div").html("R$ " + valor_venda_promo.toFixed(2))

                            //Valores para descontar
                            //var imposto  = valor_venda.toFixed(2)*(data.imposto_ym/100)
                            //var comissao  = valor_venda.toFixed(2)*(data.comissao_marketplace_ym/100)
                            var lucro = valor_venda.toFixed(2) * (lucro_original / 100)
                            $("#mc_v_204916439_hidden").val("R$ " + lucro.toFixed(2))
                            $("#mc_v_204916439").val("R$ " + lucro.toFixed(2))
                            $("#mc_v_204916439_div").html("R$ " + lucro.toFixed(2))

                            var lucro_perc = (lucro / valor_venda) * 100;
                            $("#mc_p_204916439_hidden").val(Math.round(lucro_perc.toFixed(2)) + "%")
                            $("#mc_p_204916439").val(Math.round(lucro_perc.toFixed(2)) + "%")
                            $("#mc_p_204916439_div").html(Math.round(lucro_perc.toFixed(2)) + "%")
                        }
                    });



                }
            });

            $.ajax({
                type: "POST",
                url: base_url + '/Integracao/getDadosP2',
                data: {
                },
                dataType: 'json',
                success: function (data) {
                    var taxa_comissao = data.comissao_marketplace_p2;
                    taxa_comissao = (100 - taxa_comissao) / 100;
                    var taxa_original = data.comissao_marketplace_p2 / 100;

                    var taxa_imposto = data.imposto_p2;
                    taxa_imposto = (100 - taxa_imposto) / 100;
                    var imposto_original = data.imposto_p2 / 100;

                    var taxa_lucro = data.lucro_p2;
                    var lucro_original = data.lucro_p2;
                    taxa_lucro = (100 - taxa_lucro) / 100;

                    $("#lucro_p2_hidden").val(taxa_lucro)

                    var taxa_promo = data.campanha_desconto_p2;
                    taxa_promo = (100 - taxa_promo) / 100;

                    $("#taxa_p2_hidden").val(taxa_promo)

                    var taxa_total = data.total_taxas_p2;
                    taxa_total = (100 - taxa_total) / 100;

                    var taxa_fixa = parseFloat(data.taxa_fixa_p2);

                    var valor_frete = 0;
                    var valor_venda = 0;
                    var valor_venda_promo = 0;
                    $.ajax({
                        type: "POST",
                        url: base_url + '/Integracao/getDadosFrete',
                        data: {
                            'id': data.id,
                            'tipo': 'p2'
                        },
                        dataType: 'json',
                        success: function (data) {

                            var peso_cubico = parseFloat($("#peso_cubico_produto_div").html().replace(" Kg", ""));
                            var peso_produto = parseFloat($("#peso_produto").val());

                            var peso_final = 0;
                            if (peso_cubico >= peso_produto) {
                                peso_final = peso_cubico;
                            } else {
                                peso_final = peso_produto;
                            }

                            let valor_frete = null; // Variável para armazenar o valor do frete correspondente

                            if (data.length > 0) {
                                for (let i = 0; i < data.length; i++) {
                                    const frete = data[i];

                                    // Convertendo os intervalos para números
                                    const de = parseFloat(frete.de);
                                    const ate = parseFloat(frete.ate);

                                    // Verificando se o peso_final está no intervalo
                                    if (peso_final >= de && peso_final <= ate) {
                                        valor_frete = frete.valor;
                                        $("#frete_p2").html("R$ " + valor_frete)

                                        break; // Interrompe o loop assim que encontrar o intervalo
                                    } else {
                                        $("#frete_p2").html("R$ 0")
                                    }
                                }
                            } else {
                                $("#frete_p2").html("R$ 0")
                            }


                            if (valor_frete === null || data.length < 0) {
                                mensagensErro.push("Nenhum frete foi encontrado nas configurações da Loja pessoal 1 para esse peso");

                            }
                            var custo_total_novo = custo_total.toFixed(2)
                            valor_venda = parseFloat(custo_total_novo) + taxa_fixa;
                            valor_venda = parseFloat(valor_venda) / taxa_total;

                            if (valor_venda >= 79) {
                                var custo_total_novo = custo_total.toFixed(2)
                                valor_venda = parseFloat(custo_total_novo) + parseFloat((valor_frete ?? "0").replace(",", "."));
                                valor_venda = valor_venda / taxa_total;
                                taxa_fixa = 0;
                            }
                            var taxa_origina_percentual = taxa_original;
                            var imposto_origina_percentual = imposto_original;
                            var lucro_origina_percentual = lucro_original / 100;

                            var valor_imposto_individual = valor_venda * imposto_origina_percentual;
                            var valor_taxa_individual = valor_venda * taxa_origina_percentual;
                            var valor_lucro_individual = valor_venda * lucro_origina_percentual;

                            $("#taxa_original_p2_hidden").val(taxa_origina_percentual);
                            $("#lucro_original_p2_hidden").val(lucro_origina_percentual);
                            $("#imposto_original_p2_hidden").val(imposto_origina_percentual);
                            $("#frete_original_p2_hidden").val(valor_frete);
                            $("#taxa_Fixa_original_p2_hidden").val(taxa_fixa);

                            const resultado = `
                            <span style="text-align: center"> Resumo de valores </span>
                            </br></br>
                            Valor venda: ${valor_venda.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Frete: ${valor_frete != null ? "R$ " + valor_frete.toLocaleString("pt-BR", { style: "currency", currency: "BRL" }) : "R$ 0,00"}</br>
                            Taxa: ${valor_taxa_individual.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Imposto: ${valor_imposto_individual.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Custo total: ${custo_total.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
                            Taxa fixa: ${taxa_fixa.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}`;

                            var $elemento = $("#div_principal_valor_venda_p2");

                            // Remover o title padrão para evitar conflito
                            $elemento.removeAttr("title");

                            // Exibir tooltip ao passar o mouse
                            $elemento.hover(function (event) {
                                $(".custom-tooltip").html(resultado).css({
                                    top: event.pageY + 10 + "px",
                                    left: event.pageX + 10 + "px"
                                }).fadeIn(200);
                            }, function () {
                                $(".custom-tooltip").fadeOut(200);
                            });

                            // Atualizar posição ao mover o mouse
                            $elemento.mousemove(function (event) {
                                $(".custom-tooltip").css({
                                    top: event.pageY + 10 + "px",
                                    left: event.pageX + 10 + "px"
                                });
                            });

                            var valor_venda_promo = valor_venda / taxa_promo;

                            if (valor_venda < 79 && valor_venda_promo > 79) {
                                $(".p2_icone").show()
                            } else {
                                $(".p2_icone").hide()
                            }

                            $("#valor_venda_p2_hidden").val("R$ " + valor_venda.toFixed(2))
                            $("#valor_venda_p2").val("R$ " + valor_venda.toFixed(2))
                            $("#valor_venda_p2_div").html("R$ " + valor_venda.toFixed(2))

                            $("#valor_campanha_p2").val("R$ " + valor_venda_promo.toFixed(2))
                            $("#valor_campanha_p2_div").html("R$ " + valor_venda_promo.toFixed(2))

                            //Valores para descontar
                            //var imposto  = valor_venda.toFixed(2)*(data.imposto_p2/100)
                            //var comissao  = valor_venda.toFixed(2)*(data.comissao_marketplace_p2/100)
                            var lucro = valor_venda.toFixed(2) * (lucro_original / 100)
                            $("#mc_v_p2_hidden").val("R$ " + lucro.toFixed(2))
                            $("#mc_v_p2").val("R$ " + lucro.toFixed(2))
                            $("#mc_v_p2_div").html("R$ " + lucro.toFixed(2))

                            var lucro_perc = (lucro / valor_venda) * 100;
                            $("#mc_p_p2_hidden").val(Math.round(lucro_perc.toFixed(2)) + "%")
                            $("#mc_p_p2").val(Math.round(lucro_perc.toFixed(2)) + "%")
                            $("#mc_p_p2_div").html(Math.round(lucro_perc.toFixed(2)) + "%")
                        }
                    });




                }
            });

            // Após todas as operações
            setTimeout(() => {
                if (mensagensErro.length > 0) {
                    Swal.fire({
                        title: 'Erros encontrados',
                        html: mensagensErro.join("<br><br>"), // Junta as mensagens com quebras de linha
                        icon: 'error',
                        confirmButtonColor: '#bf0f0f',
                        confirmButtonText: 'OK!'
                    });
                }
            }, 1000); // Timeout para aguardar operações assíncronas finalizarem
        });


        function atualizaToolTip(codigo, valor_venda) {

            function parseCurrency(value) {
                // Remove "R$" e converte para float, ou retorna 0 se o valor for inválido
                var numericValue = parseFloat(value.replace("R$ ", "").replace(",", "."));
                return isNaN(numericValue) ? 0 : numericValue;
            }

            var taxa_origina_percentual = $("#taxa_original_" + codigo + "_hidden").val();
            var imposto_origina_percentual = $("#imposto_original_" + codigo + "_hidden").val();
            var lucro_origina_percentual = $("#lucro_original_" + codigo + "_hidden").val();
            var valor_frete = $("#frete_original_" + codigo + "_hidden").val();
            var taxa_fixa = $("#taxa_Fixa_original_" + codigo + "_hidden").val();
            var custo_produto_div = parseCurrency($("#custo_produto").val());
            var gnre = parseCurrency($("#gnre").val());
            var embalagem = parseCurrency($("#embalagem").val());
            var coleta_full = parseCurrency($("#coleta_full").val());
            var outros = parseCurrency($("#outros").val());

            var custo_total = custo_produto_div + gnre + embalagem + coleta_full + outros;


            var valor_imposto_individual = valor_venda * imposto_origina_percentual;
            var valor_taxa_individual = valor_venda * taxa_origina_percentual;
            var valor_lucro_individual = valor_venda * lucro_origina_percentual;

            const resultado = `
            <span style="text-align: center"> Resumo de valores </span>
            </br></br>
            Valor venda: ${valor_venda.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
            Frete: ${valor_frete != null ? "R$ " + valor_frete.toLocaleString("pt-BR", { style: "currency", currency: "BRL" }) : "R$ 0,00"}</br>
            Taxa: ${valor_taxa_individual.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
            Imposto: ${valor_imposto_individual.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
            Custo total: ${custo_total.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
            Taxa fixa: ${taxa_fixa.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}`;

            var $elemento = $("#div_principal_valor_venda_" + codigo);

            // Remover o title padrão para evitar conflito
            $elemento.removeAttr("title");

            // Exibir tooltip ao passar o mouse
            $elemento.hover(function (event) {
                $(".custom-tooltip").html(resultado).css({
                    top: event.pageY + 10 + "px",
                    left: event.pageX + 10 + "px"
                }).fadeIn(200);
            }, function () {
                $(".custom-tooltip").fadeOut(200);
            });

            // Atualizar posição ao mover o mouse
            $elemento.mousemove(function (event) {
                $(".custom-tooltip").css({
                    top: event.pageY + 10 + "px",
                    left: event.pageX + 10 + "px"
                });
            });

        }


        //MERCADO LIVRE

        // Atualizar valor de venda e lucro R$ ao alterar lucro %
        $("#mc_p_203656717").on("blur", function () {
            const nova_taxa_lucro = parseFloat($("#mc_p_203656717").val().replace("%", "").replace(",", "."));
            const lucro_203656717_hidden = parseFloat($("#lucro_203656717_hidden").val().replace("R$ ", "").replace(",", "."));
            const lucro_original_percentual = 100 - (lucro_203656717_hidden * 100);
            const taxa_marketplace_original = parseFloat($("#taxa_marketplace_original_203656717_hidden").val().replace("R$ ", "").replace(",", "."));
            const taxa_original_percentual = Math.round(100 - (taxa_marketplace_original * 100));
            var nova_taxa_percentual = 100 - ((taxa_original_percentual - lucro_original_percentual) + nova_taxa_lucro);
            nova_taxa_percentual = nova_taxa_percentual / 100;
            const valor_frete = parseFloat($("#frete_203656717").val().replace("R$ ", "").replace(",", "."));
            var custo_produto_div = parseCurrency($("#custo_produto").val());
            var gnre = parseCurrency($("#gnre").val());
            var embalagem = parseCurrency($("#embalagem").val());
            var coleta_full = parseCurrency($("#coleta_full").val());
            var outros = parseCurrency($("#outros").val());

            var custo_total = custo_produto_div + gnre + embalagem + coleta_full + outros;
            var valor_venda = parseFloat(custo_total) + valor_frete;
            valor_venda = valor_venda / nova_taxa_percentual;


            var taxa_origina_percentual = $("#taxa_original_203656717_hidden").val();
            var imposto_origina_percentual = $("#imposto_original_203656717_hidden").val();
            var taxa_promo = $("#taxa_203656717_hidden").val();

            var valor_imposto_individual = valor_venda * imposto_origina_percentual;
            var valor_taxa_individual = valor_venda * taxa_origina_percentual;


            const resultado = `
            <span style="text-align: center"> Resumo de valores </span>
            </br></br>
            Valor venda: ${valor_venda.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
            Frete: ${valor_frete != null ? "R$ " + valor_frete.toLocaleString("pt-BR", { style: "currency", currency: "BRL" }) : "R$ 0,00"}</br>
            Taxa: ${valor_taxa_individual.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
            Imposto: ${valor_imposto_individual.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
            Custo total: ${custo_total.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}`;

            var $elemento = $("#div_principal_valor_venda_203656717");

            // Remover o title padrão para evitar conflito
            $elemento.removeAttr("title");

            // Exibir tooltip ao passar o mouse
            $elemento.hover(function (event) {
                $(".custom-tooltip").html(resultado).css({
                    top: event.pageY + 10 + "px",
                    left: event.pageX + 10 + "px"
                }).fadeIn(200);
            }, function () {
                $(".custom-tooltip").fadeOut(200);
            });

            // Atualizar posição ao mover o mouse
            $elemento.mousemove(function (event) {
                $(".custom-tooltip").css({
                    top: event.pageY + 10 + "px",
                    left: event.pageX + 10 + "px"
                });
            });


            var valor_venda_promo = valor_venda / taxa_promo;

            if (valor_venda < 79 && valor_venda_promo > 79) {
                $(".203656717_icone").show()
            } else {
                $(".203656717_icone").hide()
            }

            $("#valor_venda_203656717_hidden").val("R$ " + valor_venda.toFixed(2))
            $("#valor_venda_203656717").val("R$ " + valor_venda.toFixed(2))
            $("#valor_venda_203656717_div").html("R$ " + valor_venda.toFixed(2))

            $("#valor_campanha_203656717").val("R$ " + valor_venda_promo.toFixed(2))
            $("#valor_campanha_203656717_div").html("R$ " + valor_venda_promo.toFixed(2))

            var lucro = valor_venda.toFixed(2) * (nova_taxa_lucro / 100)
            $("#mc_v_203656717_hidden").val("R$ " + lucro.toFixed(2))
            $("#mc_v_203656717").val("R$ " + lucro.toFixed(2))
            $("#mc_v_203656717_div").html("R$ " + lucro.toFixed(2))
            
        });

        $("#mc_v_203656717").on("blur", function () {
            const valor_desejado = parseFloat($("#mc_v_203656717").val().replace("R$ ", "").replace(",", "."));
            const valor_venda_atual = parseFloat($("#valor_venda_203656717_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_203656717_hidden").val().replace("R$ ", "").replace(",", "."));

            if (!isNaN(valor_desejado) && !isNaN(valor_venda_atual)) {
                var valor = valor_venda_atual - valor_lucro_atual;
                var novo_valor = valor + valor_desejado
                var lucro_novo_percentual = (valor_desejado / novo_valor) * 100

                $("#mc_p_203656717").val(lucro_novo_percentual.toFixed(2) + "%")
                $("#mc_p_203656717_div").html(lucro_novo_percentual.toFixed(2) + "%")

                $("#valor_venda_203656717").val("R$ " + novo_valor.toFixed(2))
                $("#valor_venda_203656717_div").html("R$ " + novo_valor.toFixed(2))

                var taxa_promo = $("#taxa_203656717_hidden").val();

                atualizaToolTip(203656717, novo_valor)


                var valor_venda_promo = novo_valor / taxa_promo;

                $("#valor_campanha_203656717").val("R$ " + valor_venda_promo.toFixed(2))
                $("#valor_campanha_203656717_div").html("R$ " + valor_venda_promo.toFixed(2))


            }
        });

        $("#valor_venda_203656717").on("blur", function () {
            const percentual_lucro = parseFloat($("#taxa_203656717_hidden").val().replace("%", "").replace(",", "."));
            const taxa_lucro = parseFloat($("#lucro_203656717_hidden").val().replace("%", "").replace(",", "."));
            const valor_venda_atual = parseFloat($("#valor_venda_203656717").val().replace("R$ ", "").replace(",", "."));

            const valor_venda_original = parseFloat($("#valor_venda_203656717_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_203656717_hidden").val().replace("R$ ", "").replace(",", "."));

            var custo = valor_venda_original - valor_lucro_atual;
            var novo_lucro = valor_venda_atual - custo
            var perc_novo_lucro = (novo_lucro / valor_venda_original) * 100;



            if (!isNaN(novo_lucro) && !isNaN(perc_novo_lucro)) {

                $("#mc_v_203656717").val("R$ " + novo_lucro.toFixed(2))
                $("#mc_v_203656717_div").html("R$ " + novo_lucro.toFixed(2))

                $("#mc_p_203656717").val(Math.round(perc_novo_lucro.toFixed(2)) + "%")
                $("#mc_p_203656717_div").html(Math.round(perc_novo_lucro.toFixed(2)) + "%")

                var taxa_promo = $("#taxa_203656717_hidden").val();

                atualizaToolTip(203656717, valor_venda_atual)

                var valor_venda_promo = valor_venda_atual / taxa_promo;

                $("#valor_campanha_203656717").val("R$ " + valor_venda_promo.toFixed(2))
                $("#valor_campanha_203656717_div").html("R$ " + valor_venda_promo.toFixed(2))



            }
        });

        $("#mc_p_203656717").on("click", function () {
            const valor_venda_atual = parseFloat($("#valor_venda_203656717_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_203656717_hidden").val().replace("R$ ", "").replace(",", "."));


            $("#valor_venda_203656717").val("R$ " + valor_venda_atual.toFixed(2))
            $("#valor_venda_203656717_div").html("R$ " + valor_venda_atual.toFixed(2))

            $("#mc_v_203656717").val("R$ " + valor_lucro_atual.toFixed(2))
            $("#mc_v_203656717_div").html("R$ " + valor_lucro_atual.toFixed(2))

        });


        $("#mc_v_203656717").on("click", function () {
            const valor_venda_atual = parseFloat($("#valor_venda_203656717_hidden").val().replace("R$ ", "").replace(",", "."));
            const percentual_lucro = parseFloat($("#mc_p_203656717_hidden").val().replace("%", "").replace(",", "."));

            $("#valor_venda_203656717").val("R$ " + valor_venda_atual.toFixed(2))
            $("#valor_venda_203656717_div").html("R$ " + valor_venda_atual.toFixed(2))

            $("#mc_p_203656717").val(Math.round(percentual_lucro.toFixed(2)) + "%")
            $("#mc_p_203656717_div").html(Math.round(percentual_lucro.toFixed(2)) + "%")
        });

        $("#valor_venda_203656717").on("click", function () {
            var valor_lucro_atual = parseFloat($("#mc_v_203656717_hidden").val().replace("R$ ", "").replace(",", "."));
            var taxa_lucro = parseFloat($("#lucro_203656717_hidden").val().replace("%", "").replace(",", "."));
            taxa_lucro = 100 - (taxa_lucro * 100);



            $("#mc_p_203656717").val(Math.round(taxa_lucro.toFixed(2)) + "%")
            $("#mc_p_203656717_div").html(Math.round(taxa_lucro.toFixed(2)) + "%")

            $("#mc_v_203656717").val("R$ " + valor_lucro_atual.toFixed(2))
            $("#mc_v_203656717_div").html("R$ " + valor_lucro_atual.toFixed(2))

        });


        $("#frete_203656717").on("blur", function () {
            const taxa_marketplace_original = parseFloat($("#taxa_marketplace_original_203656717_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_frete = parseFloat($("#frete_203656717").val().replace("R$ ", "").replace(",", "."));
            var custo_produto_div = parseCurrency($("#custo_produto").val());
            var gnre = parseCurrency($("#gnre").val());
            var embalagem = parseCurrency($("#embalagem").val());
            var coleta_full = parseCurrency($("#coleta_full").val());
            var outros = parseCurrency($("#outros").val());

            var custo_total = custo_produto_div + gnre + embalagem + coleta_full + outros;
            var valor_venda = parseFloat(custo_total) + valor_frete;
            valor_venda = valor_venda / taxa_marketplace_original;


            var taxa_origina_percentual = $("#taxa_original_203656717_hidden").val();
            var imposto_origina_percentual = $("#imposto_original_203656717_hidden").val();
            var lucro_origina_percentual = $("#lucro_original_203656717_hidden").val();
            var taxa_promo = $("#taxa_203656717_hidden").val();

            var valor_imposto_individual = valor_venda * imposto_origina_percentual;
            var valor_taxa_individual = valor_venda * taxa_origina_percentual;
            var valor_lucro_individual = valor_venda * lucro_origina_percentual;


            const resultado = `
            <span style="text-align: center"> Resumo de valores </span>
            </br></br>
            Valor venda: ${valor_venda.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
            Frete: ${valor_frete != null ? "R$ " + valor_frete.toLocaleString("pt-BR", { style: "currency", currency: "BRL" }) : "R$ 0,00"}</br>
            Taxa: ${valor_taxa_individual.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
            Imposto: ${valor_imposto_individual.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}</br>
            Custo total: ${custo_total.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })}`;

            var $elemento = $("#div_principal_valor_venda_203656717");

            // Remover o title padrão para evitar conflito
            $elemento.removeAttr("title");

            // Exibir tooltip ao passar o mouse
            $elemento.hover(function (event) {
                $(".custom-tooltip").html(resultado).css({
                    top: event.pageY + 10 + "px",
                    left: event.pageX + 10 + "px"
                }).fadeIn(200);
            }, function () {
                $(".custom-tooltip").fadeOut(200);
            });

            // Atualizar posição ao mover o mouse
            $elemento.mousemove(function (event) {
                $(".custom-tooltip").css({
                    top: event.pageY + 10 + "px",
                    left: event.pageX + 10 + "px"
                });
            });


            var valor_venda_promo = valor_venda / taxa_promo;

            if (valor_venda < 79 && valor_venda_promo > 79) {
                $(".203656717_icone").show()
            } else {
                $(".203656717_icone").hide()
            }

            $("#valor_venda_203656717_hidden").val("R$ " + valor_venda.toFixed(2))
            $("#valor_venda_203656717").val("R$ " + valor_venda.toFixed(2))
            $("#valor_venda_203656717_div").html("R$ " + valor_venda.toFixed(2))

            $("#valor_campanha_203656717").val("R$ " + valor_venda_promo.toFixed(2))
            $("#valor_campanha_203656717_div").html("R$ " + valor_venda_promo.toFixed(2))
            
            var lucro = valor_venda.toFixed(2) * lucro_origina_percentual
            $("#mc_v_203656717_hidden").val("R$ " + lucro.toFixed(2))
            $("#mc_v_203656717").val("R$ " + lucro.toFixed(2))
            $("#mc_v_203656717_div").html("R$ " + lucro.toFixed(2))

            var lucro_perc = (lucro / valor_venda) * 100;
            $("#mc_p_203656717_hidden").val(Math.round(lucro_perc.toFixed(2)) + "%")
            $("#mc_p_203656717").val(Math.round(lucro_perc.toFixed(2)) + "%")
            $("#mc_p_203656717_div").html(Math.round(lucro_perc.toFixed(2)) + "%")



            
        });

        //MERCADO LIVRE

        //SHOPEE

        $("#mc_p_203718490").on("blur", function () {
            const percentual_lucro = parseFloat($("#mc_p_203718490").val().replace("%", "").replace(",", "."));
            const percentual_lucro_hidden = parseFloat($("#mc_p_203718490_hidden").val().replace("%", "").replace(",", "."));
            const valor_venda_atual = parseFloat($("#valor_venda_203718490_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_203718490_hidden").val().replace("R$ ", "").replace(",", "."));

            if (percentual_lucro != percentual_lucro_hidden && !isNaN(percentual_lucro) && !isNaN(valor_venda_atual)) {
                var valor = valor_venda_atual - valor_lucro_atual;
                var novo_valor = valor * (1 + percentual_lucro / 100);
                var lucro_novo = novo_valor.toFixed(2) - valor;

                $("#mc_v_203718490").val("R$ " + lucro_novo.toFixed(2))
                $("#mc_v_203718490_div").html("R$ " + lucro_novo.toFixed(2))

                $("#valor_venda_203718490").val("R$ " + novo_valor.toFixed(2))
                $("#valor_venda_203718490_div").html("R$ " + novo_valor.toFixed(2))

                var taxa_promo = $("#taxa_203718490_hidden").val();

                atualizaToolTip(203718490, novo_valor)

                var valor_venda_promo = novo_valor / taxa_promo;

                $("#valor_campanha_203718490").val("R$ " + valor_venda_promo.toFixed(2))
                $("#valor_campanha_203718490_div").html("R$ " + valor_venda_promo.toFixed(2))



            }
        });

        $("#mc_v_203718490").on("blur", function () {
            const valor_desejado = parseFloat($("#mc_v_203718490").val().replace("R$ ", "").replace(",", "."));
            const valor_venda_atual = parseFloat($("#valor_venda_203718490_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_203718490_hidden").val().replace("R$ ", "").replace(",", "."));

            if (!isNaN(valor_desejado) && !isNaN(valor_venda_atual)) {
                var valor = valor_venda_atual - valor_lucro_atual;
                var novo_valor = valor + valor_desejado
                var lucro_novo_percentual = (valor_desejado / novo_valor) * 100

                $("#mc_p_203718490").val(lucro_novo_percentual.toFixed(2) + "%")
                $("#mc_p_203718490_div").html(lucro_novo_percentual.toFixed(2) + "%")

                $("#valor_venda_203718490").val("R$ " + novo_valor.toFixed(2))
                $("#valor_venda_203718490_div").html("R$ " + novo_valor.toFixed(2))

                var taxa_promo = $("#taxa_203718490_hidden").val();

                atualizaToolTip(203718490, novo_valor)

                var valor_venda_promo = novo_valor / taxa_promo;

                $("#valor_campanha_203718490").val("R$ " + valor_venda_promo.toFixed(2))
                $("#valor_campanha_203718490_div").html("R$ " + valor_venda_promo.toFixed(2))


            }
        });

        $("#valor_venda_203718490").on("blur", function () {
            const percentual_lucro = parseFloat($("#taxa_203718490_hidden").val().replace("%", "").replace(",", "."));
            const taxa_lucro = parseFloat($("#lucro_203718490_hidden").val().replace("%", "").replace(",", "."));
            const valor_venda_atual = parseFloat($("#valor_venda_203718490").val().replace("R$ ", "").replace(",", "."));


            if (!isNaN(percentual_lucro) && !isNaN(valor_venda_atual)) {
                var percentual_lucro_novo = 100 - (taxa_lucro * 100);
                var lucro_novo = valor_venda_atual * (percentual_lucro_novo / 100);
                percentual_lucro_novo = (lucro_novo / valor_venda_atual) * 100;

                $("#mc_v_203718490").val("R$ " + lucro_novo.toFixed(2))
                $("#mc_v_203718490_div").html("R$ " + lucro_novo.toFixed(2))

                $("#mc_p_203718490").val(Math.round(percentual_lucro_novo.toFixed(2)) + "%")
                $("#mc_p_203718490_div").html(Math.round(percentual_lucro_novo.toFixed(2)) + "%")

                var taxa_promo = $("#taxa_203718490_hidden").val();

                var valor_venda_promo = valor_venda_atual / taxa_promo;

                atualizaToolTip(203718490, valor_venda_atual)

                $("#valor_campanha_203718490").val("R$ " + valor_venda_promo.toFixed(2))
                $("#valor_campanha_203718490_div").html("R$ " + valor_venda_promo.toFixed(2))



            }
        });

        $("#mc_p_203718490").on("click", function () {
            const valor_venda_atual = parseFloat($("#valor_venda_203718490_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_203718490_hidden").val().replace("R$ ", "").replace(",", "."));


            $("#valor_venda_203718490").val("R$ " + valor_venda_atual.toFixed(2))
            $("#valor_venda_203718490_div").html("R$ " + valor_venda_atual.toFixed(2))

            $("#mc_v_203718490").val("R$ " + valor_lucro_atual.toFixed(2))
            $("#mc_v_203718490_div").html("R$ " + valor_lucro_atual.toFixed(2))

        });

        $("#mc_v_203718490_div").on("click", function () {
            const valor_venda_atual = parseFloat($("#valor_venda_203718490_hidden").val().replace("R$ ", "").replace(",", "."));
            const percentual_lucro = parseFloat($("#mc_p_203718490_hidden").val().replace("%", "").replace(",", "."));




            $("#valor_venda_203718490").val("R$ " + valor_venda_atual.toFixed(2))
            $("#valor_venda_203718490_div").html("R$ " + valor_venda_atual.toFixed(2))

            $("#mc_p_203718490").val(Math.round(percentual_lucro.toFixed(2)) + "%")
            $("#mc_p_203718490_div").html(Math.round(percentual_lucro.toFixed(2)) + "%")
        });

        $("#mc_v_203718490").on("click", function () {
            const valor_venda_atual = parseFloat($("#valor_venda_203718490_hidden").val().replace("R$ ", "").replace(",", "."));
            const percentual_lucro = parseFloat($("#mc_p_203718490_hidden").val().replace("%", "").replace(",", "."));

            $("#valor_venda_203718490").val("R$ " + valor_venda_atual.toFixed(2))
            $("#valor_venda_203718490_div").html("R$ " + valor_venda_atual.toFixed(2))

            $("#mc_p_203718490").val(Math.round(percentual_lucro.toFixed(2)) + "%")
            $("#mc_p_203718490_div").html(Math.round(percentual_lucro.toFixed(2)) + "%")
        });

        $("#valor_venda_203718490").on("click", function () {
            var valor_lucro_atual = parseFloat($("#mc_v_203718490_hidden").val().replace("R$ ", "").replace(",", "."));
            var taxa_lucro = parseFloat($("#lucro_203718490_hidden").val().replace("%", "").replace(",", "."));
            taxa_lucro = 100 - (taxa_lucro * 100);



            $("#mc_p_203718490").val(Math.round(taxa_lucro.toFixed(2)) + "%")
            $("#mc_p_203718490_div").html(Math.round(taxa_lucro.toFixed(2)) + "%")

            $("#mc_v_203718490").val("R$ " + valor_lucro_atual.toFixed(2))
            $("#mc_v_203718490_div").html("R$ " + valor_lucro_atual.toFixed(2))

        });

        //SHOPEE

        //MAGALU

        $("#mc_p_203780244").on("blur", function () {
            const percentual_lucro = parseFloat($("#mc_p_203780244").val().replace("%", "").replace(",", "."));
            const percentual_lucro_hidden = parseFloat($("#mc_p_203780244_hidden").val().replace("%", "").replace(",", "."));
            const valor_venda_atual = parseFloat($("#valor_venda_203780244_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_203780244_hidden").val().replace("R$ ", "").replace(",", "."));

            if (percentual_lucro != percentual_lucro_hidden && !isNaN(percentual_lucro) && !isNaN(valor_venda_atual)) {
                var valor = valor_venda_atual - valor_lucro_atual;
                var novo_valor = valor * (1 + percentual_lucro / 100);
                var lucro_novo = novo_valor.toFixed(2) - valor;

                $("#mc_v_203780244").val("R$ " + lucro_novo.toFixed(2))
                $("#mc_v_203780244_div").html("R$ " + lucro_novo.toFixed(2))

                $("#valor_venda_203780244").val("R$ " + novo_valor.toFixed(2))
                $("#valor_venda_203780244_div").html("R$ " + novo_valor.toFixed(2))

                var taxa_promo = $("#taxa_203780244_hidden").val();

                var valor_venda_promo = novo_valor / taxa_promo;

                atualizaToolTip(203780244, novo_valor)

                $("#valor_campanha_203780244").val("R$ " + valor_venda_promo.toFixed(2))
                $("#valor_campanha_203780244_div").html("R$ " + valor_venda_promo.toFixed(2))



            }
        });

        $("#mc_v_203780244").on("blur", function () {
            const valor_desejado = parseFloat($("#mc_v_203780244").val().replace("R$ ", "").replace(",", "."));
            const valor_venda_atual = parseFloat($("#valor_venda_203780244_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_203780244_hidden").val().replace("R$ ", "").replace(",", "."));

            if (!isNaN(valor_desejado) && !isNaN(valor_venda_atual)) {
                var valor = valor_venda_atual - valor_lucro_atual;
                var novo_valor = valor + valor_desejado
                var lucro_novo_percentual = (valor_desejado / novo_valor) * 100

                $("#mc_p_203780244").val(lucro_novo_percentual.toFixed(2) + "%")
                $("#mc_p_203780244_div").html(lucro_novo_percentual.toFixed(2) + "%")

                $("#valor_venda_203780244").val("R$ " + novo_valor.toFixed(2))
                $("#valor_venda_203780244_div").html("R$ " + novo_valor.toFixed(2))

                var taxa_promo = $("#taxa_203780244_hidden").val();

                var valor_venda_promo = novo_valor / taxa_promo;
                atualizaToolTip(203780244, novo_valor)

                $("#valor_campanha_203780244").val("R$ " + valor_venda_promo.toFixed(2))
                $("#valor_campanha_203780244_div").html("R$ " + valor_venda_promo.toFixed(2))


            }
        });

        $("#valor_venda_203780244").on("blur", function () {
            const percentual_lucro = parseFloat($("#taxa_203780244_hidden").val().replace("%", "").replace(",", "."));
            const taxa_lucro = parseFloat($("#lucro_203780244_hidden").val().replace("%", "").replace(",", "."));
            const valor_venda_atual = parseFloat($("#valor_venda_203780244").val().replace("R$ ", "").replace(",", "."));


            if (!isNaN(percentual_lucro) && !isNaN(valor_venda_atual)) {
                var percentual_lucro_novo = 100 - (taxa_lucro * 100);
                var lucro_novo = valor_venda_atual * (percentual_lucro_novo / 100);
                percentual_lucro_novo = (lucro_novo / valor_venda_atual) * 100;

                $("#mc_v_203780244").val("R$ " + lucro_novo.toFixed(2))
                $("#mc_v_203780244_div").html("R$ " + lucro_novo.toFixed(2))

                $("#mc_p_203780244").val(Math.round(percentual_lucro_novo.toFixed(2)) + "%")
                $("#mc_p_203780244_div").html(Math.round(percentual_lucro_novo.toFixed(2)) + "%")

                var taxa_promo = $("#taxa_203780244_hidden").val();

                atualizaToolTip(203780244, valor_venda_atual)

                var valor_venda_promo = valor_venda_atual / taxa_promo;

                $("#valor_campanha_203780244").val("R$ " + valor_venda_promo.toFixed(2))
                $("#valor_campanha_203780244_div").html("R$ " + valor_venda_promo.toFixed(2))



            }
        });

        $("#mc_p_203780244").on("click", function () {
            const valor_venda_atual = parseFloat($("#valor_venda_203780244_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_203780244_hidden").val().replace("R$ ", "").replace(",", "."));


            $("#valor_venda_203780244").val("R$ " + valor_venda_atual.toFixed(2))
            $("#valor_venda_203780244_div").html("R$ " + valor_venda_atual.toFixed(2))

            $("#mc_v_203780244").val("R$ " + valor_lucro_atual.toFixed(2))
            $("#mc_v_203780244_div").html("R$ " + valor_lucro_atual.toFixed(2))

        });

        $("#mc_v_203780244_div").on("click", function () {
            const valor_venda_atual = parseFloat($("#valor_venda_203780244_hidden").val().replace("R$ ", "").replace(",", "."));
            const percentual_lucro = parseFloat($("#mc_p_203780244_hidden").val().replace("%", "").replace(",", "."));




            $("#valor_venda_203780244").val("R$ " + valor_venda_atual.toFixed(2))
            $("#valor_venda_203780244_div").html("R$ " + valor_venda_atual.toFixed(2))

            $("#mc_p_203780244").val(Math.round(percentual_lucro.toFixed(2)) + "%")
            $("#mc_p_203780244_div").html(Math.round(percentual_lucro.toFixed(2)) + "%")
        });

        $("#mc_v_203780244").on("click", function () {
            const valor_venda_atual = parseFloat($("#valor_venda_203780244_hidden").val().replace("R$ ", "").replace(",", "."));
            const percentual_lucro = parseFloat($("#mc_p_203780244_hidden").val().replace("%", "").replace(",", "."));

            $("#valor_venda_203780244").val("R$ " + valor_venda_atual.toFixed(2))
            $("#valor_venda_203780244_div").html("R$ " + valor_venda_atual.toFixed(2))

            $("#mc_p_203780244").val(Math.round(percentual_lucro.toFixed(2)) + "%")
            $("#mc_p_203780244_div").html(Math.round(percentual_lucro.toFixed(2)) + "%")
        });

        $("#valor_venda_203780244").on("click", function () {
            var valor_lucro_atual = parseFloat($("#mc_v_203780244_hidden").val().replace("R$ ", "").replace(",", "."));
            var taxa_lucro = parseFloat($("#lucro_203780244_hidden").val().replace("%", "").replace(",", "."));
            taxa_lucro = 100 - (taxa_lucro * 100);



            $("#mc_p_203780244").val(Math.round(taxa_lucro.toFixed(2)) + "%")
            $("#mc_p_203780244_div").html(Math.round(taxa_lucro.toFixed(2)) + "%")

            $("#mc_v_203780244").val("R$ " + valor_lucro_atual.toFixed(2))
            $("#mc_v_203780244_div").html("R$ " + valor_lucro_atual.toFixed(2))

        });

        //MAGALU

        //YAMPI

        $("#mc_p_204916439").on("blur", function () {
            const percentual_lucro = parseFloat($("#mc_p_204916439").val().replace("%", "").replace(",", "."));
            const percentual_lucro_hidden = parseFloat($("#mc_p_204916439_hidden").val().replace("%", "").replace(",", "."));
            const valor_venda_atual = parseFloat($("#valor_venda_204916439_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_204916439_hidden").val().replace("R$ ", "").replace(",", "."));

            if (percentual_lucro != percentual_lucro_hidden && !isNaN(percentual_lucro) && !isNaN(valor_venda_atual)) {
                var valor = valor_venda_atual - valor_lucro_atual;
                var novo_valor = valor * (1 + percentual_lucro / 100);
                var lucro_novo = novo_valor.toFixed(2) - valor;

                $("#mc_v_204916439").val("R$ " + lucro_novo.toFixed(2))
                $("#mc_v_204916439_div").html("R$ " + lucro_novo.toFixed(2))

                $("#valor_venda_204916439").val("R$ " + novo_valor.toFixed(2))
                $("#valor_venda_204916439_div").html("R$ " + novo_valor.toFixed(2))

                var taxa_promo = $("#taxa_204916439_hidden").val();

                atualizaToolTip(204916439, novo_valor)

                var valor_venda_promo = novo_valor / taxa_promo;

                $("#valor_campanha_204916439").val("R$ " + valor_venda_promo.toFixed(2))
                $("#valor_campanha_204916439_div").html("R$ " + valor_venda_promo.toFixed(2))



            }
        });

        $("#mc_v_204916439").on("blur", function () {
            const valor_desejado = parseFloat($("#mc_v_204916439").val().replace("R$ ", "").replace(",", "."));
            const valor_venda_atual = parseFloat($("#valor_venda_204916439_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_204916439_hidden").val().replace("R$ ", "").replace(",", "."));

            if (!isNaN(valor_desejado) && !isNaN(valor_venda_atual)) {
                var valor = valor_venda_atual - valor_lucro_atual;
                var novo_valor = valor + valor_desejado
                var lucro_novo_percentual = (valor_desejado / novo_valor) * 100

                $("#mc_p_204916439").val(lucro_novo_percentual.toFixed(2) + "%")
                $("#mc_p_204916439_div").html(lucro_novo_percentual.toFixed(2) + "%")

                $("#valor_venda_204916439").val("R$ " + novo_valor.toFixed(2))
                $("#valor_venda_204916439_div").html("R$ " + novo_valor.toFixed(2))

                var taxa_promo = $("#taxa_204916439_hidden").val();

                var valor_venda_promo = novo_valor / taxa_promo;

                atualizaToolTip(204916439, novo_valor)

                $("#valor_campanha_204916439").val("R$ " + valor_venda_promo.toFixed(2))
                $("#valor_campanha_204916439_div").html("R$ " + valor_venda_promo.toFixed(2))


            }
        });

        $("#valor_venda_204916439").on("blur", function () {
            const percentual_lucro = parseFloat($("#taxa_204916439_hidden").val().replace("%", "").replace(",", "."));
            const taxa_lucro = parseFloat($("#lucro_204916439_hidden").val().replace("%", "").replace(",", "."));
            const valor_venda_atual = parseFloat($("#valor_venda_204916439").val().replace("R$ ", "").replace(",", "."));


            if (!isNaN(percentual_lucro) && !isNaN(valor_venda_atual)) {
                var percentual_lucro_novo = 100 - (taxa_lucro * 100);
                var lucro_novo = valor_venda_atual * (percentual_lucro_novo / 100);
                percentual_lucro_novo = (lucro_novo / valor_venda_atual) * 100;

                $("#mc_v_204916439").val("R$ " + lucro_novo.toFixed(2))
                $("#mc_v_204916439_div").html("R$ " + lucro_novo.toFixed(2))

                $("#mc_p_204916439").val(Math.round(percentual_lucro_novo.toFixed(2)) + "%")
                $("#mc_p_204916439_div").html(Math.round(percentual_lucro_novo.toFixed(2)) + "%")

                var taxa_promo = $("#taxa_204916439_hidden").val();

                atualizaToolTip(204916439, valor_venda_atual)

                var valor_venda_promo = valor_venda_atual / taxa_promo;

                $("#valor_campanha_204916439").val("R$ " + valor_venda_promo.toFixed(2))
                $("#valor_campanha_204916439_div").html("R$ " + valor_venda_promo.toFixed(2))



            }
        });

        $("#mc_p_204916439").on("click", function () {
            const valor_venda_atual = parseFloat($("#valor_venda_204916439_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_204916439_hidden").val().replace("R$ ", "").replace(",", "."));


            $("#valor_venda_204916439").val("R$ " + valor_venda_atual.toFixed(2))
            $("#valor_venda_204916439_div").html("R$ " + valor_venda_atual.toFixed(2))

            $("#mc_v_204916439").val("R$ " + valor_lucro_atual.toFixed(2))
            $("#mc_v_204916439_div").html("R$ " + valor_lucro_atual.toFixed(2))

        });

        $("#mc_v_204916439_div").on("click", function () {
            const valor_venda_atual = parseFloat($("#valor_venda_204916439_hidden").val().replace("R$ ", "").replace(",", "."));
            const percentual_lucro = parseFloat($("#mc_p_204916439_hidden").val().replace("%", "").replace(",", "."));




            $("#valor_venda_204916439").val("R$ " + valor_venda_atual.toFixed(2))
            $("#valor_venda_204916439_div").html("R$ " + valor_venda_atual.toFixed(2))

            $("#mc_p_204916439").val(Math.round(percentual_lucro.toFixed(2)) + "%")
            $("#mc_p_204916439_div").html(Math.round(percentual_lucro.toFixed(2)) + "%")
        });

        $("#mc_v_204916439").on("click", function () {
            const valor_venda_atual = parseFloat($("#valor_venda_204916439_hidden").val().replace("R$ ", "").replace(",", "."));
            const percentual_lucro = parseFloat($("#mc_p_204916439_hidden").val().replace("%", "").replace(",", "."));

            $("#valor_venda_204916439").val("R$ " + valor_venda_atual.toFixed(2))
            $("#valor_venda_204916439_div").html("R$ " + valor_venda_atual.toFixed(2))

            $("#mc_p_204916439").val(Math.round(percentual_lucro.toFixed(2)) + "%")
            $("#mc_p_204916439_div").html(Math.round(percentual_lucro.toFixed(2)) + "%")
        });

        $("#valor_venda_204916439").on("click", function () {
            var valor_lucro_atual = parseFloat($("#mc_v_204916439_hidden").val().replace("R$ ", "").replace(",", "."));
            var taxa_lucro = parseFloat($("#lucro_204916439_hidden").val().replace("%", "").replace(",", "."));
            taxa_lucro = 100 - (taxa_lucro * 100);



            $("#mc_p_204916439").val(Math.round(taxa_lucro.toFixed(2)) + "%")
            $("#mc_p_204916439_div").html(Math.round(taxa_lucro.toFixed(2)) + "%")

            $("#mc_v_204916439").val("R$ " + valor_lucro_atual.toFixed(2))
            $("#mc_v_204916439_div").html("R$ " + valor_lucro_atual.toFixed(2))

        });

        //YAMPI

        //PESSOAL 1

        $("#mc_p_p1").on("blur", function () {
            const percentual_lucro = parseFloat($("#mc_p_p1").val().replace("%", "").replace(",", "."));
            const percentual_lucro_hidden = parseFloat($("#mc_p_p1_hidden").val().replace("%", "").replace(",", "."));
            const valor_venda_atual = parseFloat($("#valor_venda_p1_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_p1_hidden").val().replace("R$ ", "").replace(",", "."));

            if (percentual_lucro != percentual_lucro_hidden && !isNaN(percentual_lucro) && !isNaN(valor_venda_atual)) {
                var valor = valor_venda_atual - valor_lucro_atual;
                var novo_valor = valor * (1 + percentual_lucro / 100);
                var lucro_novo = novo_valor.toFixed(2) - valor;

                $("#mc_v_p1").val("R$ " + lucro_novo.toFixed(2))
                $("#mc_v_p1_div").html("R$ " + lucro_novo.toFixed(2))

                $("#valor_venda_p1").val("R$ " + novo_valor.toFixed(2))
                $("#valor_venda_p1_div").html("R$ " + novo_valor.toFixed(2))

                var taxa_promo = $("#taxa_p1_hidden").val();

                var valor_venda_promo = novo_valor / taxa_promo;

                atualizaToolTip("p1", novo_valor)

                $("#valor_campanha_p1").val("R$ " + valor_venda_promo.toFixed(2))
                $("#valor_campanha_p1_div").html("R$ " + valor_venda_promo.toFixed(2))



            }
        });

        $("#mc_v_p1").on("blur", function () {
            const valor_desejado = parseFloat($("#mc_v_p1").val().replace("R$ ", "").replace(",", "."));
            const valor_venda_atual = parseFloat($("#valor_venda_p1_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_p1_hidden").val().replace("R$ ", "").replace(",", "."));

            if (!isNaN(valor_desejado) && !isNaN(valor_venda_atual)) {
                var valor = valor_venda_atual - valor_lucro_atual;
                var novo_valor = valor + valor_desejado
                var lucro_novo_percentual = (valor_desejado / novo_valor) * 100

                $("#mc_p_p1").val(lucro_novo_percentual.toFixed(2) + "%")
                $("#mc_p_p1_div").html(lucro_novo_percentual.toFixed(2) + "%")

                $("#valor_venda_p1").val("R$ " + novo_valor.toFixed(2))
                $("#valor_venda_p1_div").html("R$ " + novo_valor.toFixed(2))

                var taxa_promo = $("#taxa_p1_hidden").val();

                atualizaToolTip("p1", novo_valor)

                var valor_venda_promo = novo_valor / taxa_promo;

                $("#valor_campanha_p1").val("R$ " + valor_venda_promo.toFixed(2))
                $("#valor_campanha_p1_div").html("R$ " + valor_venda_promo.toFixed(2))


            }
        });

        $("#valor_venda_p1").on("blur", function () {
            const percentual_lucro = parseFloat($("#taxa_p1_hidden").val().replace("%", "").replace(",", "."));
            const taxa_lucro = parseFloat($("#lucro_p1_hidden").val().replace("%", "").replace(",", "."));
            const valor_venda_atual = parseFloat($("#valor_venda_p1").val().replace("R$ ", "").replace(",", "."));

            const valor_venda_original = parseFloat($("#valor_venda_p1_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_p1_hidden").val().replace("R$ ", "").replace(",", "."));

            var custo = valor_venda_original - valor_lucro_atual;
            var novo_lucro = valor_venda_atual - custo
            var perc_novo_lucro = (novo_lucro / valor_venda_original) * 100;



            if (!isNaN(novo_lucro) && !isNaN(perc_novo_lucro)) {

                $("#mc_v_p1").val("R$ " + novo_lucro.toFixed(2))
                $("#mc_v_p1_div").html("R$ " + novo_lucro.toFixed(2))

                $("#mc_p_p1").val(Math.round(perc_novo_lucro.toFixed(2)) + "%")
                $("#mc_p_p1_div").html(Math.round(perc_novo_lucro.toFixed(2)) + "%")

                var taxa_promo = $("#taxa_p1_hidden").val();

                atualizaToolTip("p1", valor_venda_atual)

                var valor_venda_promo = valor_venda_atual / taxa_promo;

                $("#valor_campanha_p1").val("R$ " + valor_venda_promo.toFixed(2))
                $("#valor_campanha_p1_div").html("R$ " + valor_venda_promo.toFixed(2))



            }
        });

        $("#mc_p_p1").on("click", function () {
            const valor_venda_atual = parseFloat($("#valor_venda_p1_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_p1_hidden").val().replace("R$ ", "").replace(",", "."));


            $("#valor_venda_p1").val("R$ " + valor_venda_atual.toFixed(2))
            $("#valor_venda_p1_div").html("R$ " + valor_venda_atual.toFixed(2))

            $("#mc_v_p1").val("R$ " + valor_lucro_atual.toFixed(2))
            $("#mc_v_p1_div").html("R$ " + valor_lucro_atual.toFixed(2))

        });

        $("#mc_v_p1_div").on("click", function () {
            const valor_venda_atual = parseFloat($("#valor_venda_p1_hidden").val().replace("R$ ", "").replace(",", "."));
            const percentual_lucro = parseFloat($("#mc_p_p1_hidden").val().replace("%", "").replace(",", "."));




            $("#valor_venda_p1").val("R$ " + valor_venda_atual.toFixed(2))
            $("#valor_venda_p1_div").html("R$ " + valor_venda_atual.toFixed(2))

            $("#mc_p_p1").val(Math.round(percentual_lucro.toFixed(2)) + "%")
            $("#mc_p_p1_div").html(Math.round(percentual_lucro.toFixed(2)) + "%")
        });

        $("#mc_v_p1").on("click", function () {
            const valor_venda_atual = parseFloat($("#valor_venda_p1_hidden").val().replace("R$ ", "").replace(",", "."));
            const percentual_lucro = parseFloat($("#mc_p_p1_hidden").val().replace("%", "").replace(",", "."));

            $("#valor_venda_p1").val("R$ " + valor_venda_atual.toFixed(2))
            $("#valor_venda_p1_div").html("R$ " + valor_venda_atual.toFixed(2))

            $("#mc_p_p1").val(Math.round(percentual_lucro.toFixed(2)) + "%")
            $("#mc_p_p1_div").html(Math.round(percentual_lucro.toFixed(2)) + "%")
        });

        $("#valor_venda_p1").on("click", function () {
            var valor_lucro_atual = parseFloat($("#mc_v_p1_hidden").val().replace("R$ ", "").replace(",", "."));
            var taxa_lucro = parseFloat($("#lucro_p1_hidden").val().replace("%", "").replace(",", "."));
            taxa_lucro = 100 - (taxa_lucro * 100);



            $("#mc_p_p1").val(Math.round(taxa_lucro.toFixed(2)) + "%")
            $("#mc_p_p1_div").html(Math.round(taxa_lucro.toFixed(2)) + "%")

            $("#mc_v_p1").val("R$ " + valor_lucro_atual.toFixed(2))
            $("#mc_v_p1_div").html("R$ " + valor_lucro_atual.toFixed(2))

        });


        //PESSOAL 1

        //PESSOAL 2

        $("#mc_p_p2").on("blur", function () {
            const percentual_lucro = parseFloat($("#mc_p_p2").val().replace("%", "").replace(",", "."));
            const percentual_lucro_hidden = parseFloat($("#mc_p_p2_hidden").val().replace("%", "").replace(",", "."));
            const valor_venda_atual = parseFloat($("#valor_venda_p2_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_p2_hidden").val().replace("R$ ", "").replace(",", "."));

            if (percentual_lucro != percentual_lucro_hidden && !isNaN(percentual_lucro) && !isNaN(valor_venda_atual)) {
                var valor = valor_venda_atual - valor_lucro_atual;
                var novo_valor = valor * (1 + percentual_lucro / 100);
                var lucro_novo = novo_valor.toFixed(2) - valor;

                $("#mc_v_p2").val("R$ " + lucro_novo.toFixed(2))
                $("#mc_v_p2_div").html("R$ " + lucro_novo.toFixed(2))

                $("#valor_venda_p2").val("R$ " + novo_valor.toFixed(2))
                $("#valor_venda_p2_div").html("R$ " + novo_valor.toFixed(2))

                var taxa_promo = $("#taxa_p2_hidden").val();

                atualizaToolTip("p2", novo_valor)

                var valor_venda_promo = novo_valor / taxa_promo;

                $("#valor_campanha_p2").val("R$ " + valor_venda_promo.toFixed(2))
                $("#valor_campanha_p2_div").html("R$ " + valor_venda_promo.toFixed(2))



            }
        });

        $("#mc_v_p2").on("blur", function () {
            const valor_desejado = parseFloat($("#mc_v_p2").val().replace("R$ ", "").replace(",", "."));
            const valor_venda_atual = parseFloat($("#valor_venda_p2_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_p2_hidden").val().replace("R$ ", "").replace(",", "."));

            if (!isNaN(valor_desejado) && !isNaN(valor_venda_atual)) {
                var valor = valor_venda_atual - valor_lucro_atual;
                var novo_valor = valor + valor_desejado
                var lucro_novo_percentual = (valor_desejado / novo_valor) * 100

                $("#mc_p_p2").val(lucro_novo_percentual.toFixed(2) + "%")
                $("#mc_p_p2_div").html(lucro_novo_percentual.toFixed(2) + "%")

                $("#valor_venda_p2").val("R$ " + novo_valor.toFixed(2))
                $("#valor_venda_p2_div").html("R$ " + novo_valor.toFixed(2))

                var taxa_promo = $("#taxa_p2_hidden").val();

                atualizaToolTip("p2", novo_valor)

                var valor_venda_promo = novo_valor / taxa_promo;

                $("#valor_campanha_p2").val("R$ " + valor_venda_promo.toFixed(2))
                $("#valor_campanha_p2_div").html("R$ " + valor_venda_promo.toFixed(2))


            }
        });

        $("#valor_venda_p2").on("blur", function () {
            const percentual_lucro = parseFloat($("#taxa_p2_hidden").val().replace("%", "").replace(",", "."));
            const taxa_lucro = parseFloat($("#lucro_p2_hidden").val().replace("%", "").replace(",", "."));
            const valor_venda_atual = parseFloat($("#valor_venda_p2").val().replace("R$ ", "").replace(",", "."));


            if (!isNaN(percentual_lucro) && !isNaN(valor_venda_atual)) {
                var percentual_lucro_novo = 100 - (taxa_lucro * 100);
                var lucro_novo = valor_venda_atual * (percentual_lucro_novo / 100);
                percentual_lucro_novo = (lucro_novo / valor_venda_atual) * 100;

                $("#mc_v_p2").val("R$ " + lucro_novo.toFixed(2))
                $("#mc_v_p2_div").html("R$ " + lucro_novo.toFixed(2))

                $("#mc_p_p2").val(Math.round(percentual_lucro_novo.toFixed(2)) + "%")
                $("#mc_p_p2_div").html(Math.round(percentual_lucro_novo.toFixed(2)) + "%")

                var taxa_promo = $("#taxa_p2_hidden").val();

                atualizaToolTip("p2", valor_venda_atual)

                var valor_venda_promo = valor_venda_atual / taxa_promo;

                $("#valor_campanha_p2").val("R$ " + valor_venda_promo.toFixed(2))
                $("#valor_campanha_p2_div").html("R$ " + valor_venda_promo.toFixed(2))



            }
        });

        $("#mc_p_p2").on("click", function () {
            const valor_venda_atual = parseFloat($("#valor_venda_p2_hidden").val().replace("R$ ", "").replace(",", "."));
            const valor_lucro_atual = parseFloat($("#mc_v_p2_hidden").val().replace("R$ ", "").replace(",", "."));


            $("#valor_venda_p2").val("R$ " + valor_venda_atual.toFixed(2))
            $("#valor_venda_p2_div").html("R$ " + valor_venda_atual.toFixed(2))

            $("#mc_v_p2").val("R$ " + valor_lucro_atual.toFixed(2))
            $("#mc_v_p2_div").html("R$ " + valor_lucro_atual.toFixed(2))

        });

        $("#mc_v_p2_div").on("click", function () {
            const valor_venda_atual = parseFloat($("#valor_venda_p2_hidden").val().replace("R$ ", "").replace(",", "."));
            const percentual_lucro = parseFloat($("#mc_p_p2_hidden").val().replace("%", "").replace(",", "."));




            $("#valor_venda_p2").val("R$ " + valor_venda_atual.toFixed(2))
            $("#valor_venda_p2_div").html("R$ " + valor_venda_atual.toFixed(2))

            $("#mc_p_p2").val(Math.round(percentual_lucro.toFixed(2)) + "%")
            $("#mc_p_p2_div").html(Math.round(percentual_lucro.toFixed(2)) + "%")
        });

        $("#mc_v_p2").on("click", function () {
            const valor_venda_atual = parseFloat($("#valor_venda_p2_hidden").val().replace("R$ ", "").replace(",", "."));
            const percentual_lucro = parseFloat($("#mc_p_p2_hidden").val().replace("%", "").replace(",", "."));

            $("#valor_venda_p2").val("R$ " + valor_venda_atual.toFixed(2))
            $("#valor_venda_p2_div").html("R$ " + valor_venda_atual.toFixed(2))

            $("#mc_p_p2").val(Math.round(percentual_lucro.toFixed(2)) + "%")
            $("#mc_p_p2_div").html(Math.round(percentual_lucro.toFixed(2)) + "%")
        });

        $("#valor_venda_p2").on("click", function () {
            var valor_lucro_atual = parseFloat($("#mc_v_p2_hidden").val().replace("R$ ", "").replace(",", "."));
            var taxa_lucro = parseFloat($("#lucro_p2_hidden").val().replace("%", "").replace(",", "."));
            taxa_lucro = 100 - (taxa_lucro * 100);



            $("#mc_p_p2").val(Math.round(taxa_lucro.toFixed(2)) + "%")
            $("#mc_p_p2_div").html(Math.round(taxa_lucro.toFixed(2)) + "%")

            $("#mc_v_p2").val("R$ " + valor_lucro_atual.toFixed(2))
            $("#mc_v_p2_div").html("R$ " + valor_lucro_atual.toFixed(2))

        });

        //PESSOAL 2

        function calcularPesoCubico() {
            // Obtém os valores dos campos
            var largura = parseFloat($("#largura_produto").val()) || 0;
            var altura = parseFloat($("#altura_produto").val()) || 0;
            var profundidade = parseFloat($("#profundidade_produto").val()) || 0;

            // Calcula o peso cúbico
            var peso_cubico = (largura * altura * profundidade) / 6000;

            // Atualiza o valor do peso cúbico no div correspondente
            $("#peso_cubico_produto_div").html(peso_cubico.toFixed(2) + " Kg");
        }

        // Adiciona o evento blur aos três campos
        $("#largura_produto, #altura_produto, #profundidade_produto").on('blur', function () {
            calcularPesoCubico();
        });

        function loadingDeTela() {
            Swal.fire({
                title: '',
                html: '<strong> <h2> Filtrando dados. </br> Isso pode levar alguns segundos!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                didOpen: () => { Swal.showLoading(); },
                icon: "warning",
            })


            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguardar só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 10000);

            setTimeout(() => {
                formatCurrency();
                //formatCurrencyTabela();
                Swal.close();

            }, 45000);
        }

        function loadingDeTelaCurto() {
            Swal.fire({
                title: '',
                html: '<strong> <h2> Filtrando dados. </br> Isso pode levar alguns segundos!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                didOpen: () => { Swal.showLoading(); },
                icon: "warning",
            })


            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguardar só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 8000);

            setTimeout(() => {
                formatCurrency();
                //formatCurrencyTabela();
                Swal.close();

            }, 10000);
        }

        $('#envia_form_separacao').click(function () {

            $("#frm_pesquisa").attr("action", "/Integracao/blingSeparacao");
            $("#frm_pesquisa").attr("target", "");


            Swal.fire({
                title: '',
                html: '<strong> <h2> Filtrando dados. </br> Isso pode levar alguns segundos!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                didOpen: () => { Swal.showLoading(); },
                icon: "warning",
            })

            $('#frm_pesquisa').submit();

            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguardar só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 50000);

            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguardar só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 90000);




        });

        $("#imprimir_mercadorias").click(function () {
            window.location.href = "/Integracao/imprimirMercadorias";
        })

        $("#agrupar_pedido").click(function () {
            var data_inicial_tabela = $("#data_inicial_tabela").val()
            var data_final_tabela = $("#data_final_tabela").val()
            var numero_pedido = $("#numero_pedido").val()
            window.location.href = "/Integracao/blingSeparacao?data_inicial_tabela=" + data_inicial_tabela + "&data_final_tabela=" + data_final_tabela + "&numero_pedido=" + numero_pedido + "&agrupar_pedido=S";
        })

        $("#deagrupar_pedido").click(function () {
            var data_inicial_tabela = $("#data_inicial_tabela").val()
            var data_final_tabela = $("#data_final_tabela").val()
            var numero_pedido = $("#numero_pedido").val()
            window.location.href = "/Integracao/blingSeparacao?data_inicial_tabela=" + data_inicial_tabela + "&data_final_tabela=" + data_final_tabela + "&numero_pedido=" + numero_pedido + "&agrupar_pedido=N";
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


        $(".repor-localizacao").on('click', function () {
            // Extraia o ID do pedido de separação diretamente do atributo data
            var idPedidoSeparacao = $(this).data('id-pedido-separacao');

            // Verifique se o checkbox está marcado ou não
            var reporLocalizacao = $(this).is(':checked') ? 1 : 0;

            // Faça a requisição AJAX
            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/atualizaTmpPedido',
                data: {
                    'idPedidoSeparacao': idPedidoSeparacao,
                    'reporLocalizacao': reporLocalizacao
                },
                dataType: 'json',
                success: function (data) {
                    if (data != "") {
                        // Adicione o que quiser fazer com a resposta do servidor
                    }
                }
            });
        });

        // Botão Obs - Abrir modal
        $(".btn-obs").on('click', function () {
            var idPedidoSeparacao = $(this).data('id-pedido-separacao');
            var numeroPedido = $(this).data('numero-pedido');

            //usa ajax pra buscar a ovs a aprtir do pedido
            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/getPedidoAttTmpObservacao',
                data: {
                    'numeroPedido': numeroPedido
                },
                dataType: 'json',
                success: function (data) {
                    if (data != "") {
                        $("#observacao").val(data.OBSERVACAO);
                    }
                }
            });
            
            // Preencher os campos da modal
            $("#idPedidoSeparacao").val(idPedidoSeparacao);
            $("#numeroPedido").val(numeroPedido);
            $("#numeroPedidoModal").text(numeroPedido);
            $("#observacao").val('');
            
            // Abrir a modal
            $("#modalObservacao").modal('show');
        });

        // Salvar observação
        $("#salvarObservacao").on('click', function () {
            var idPedidoSeparacao = $("#idPedidoSeparacao").val();
            var numeroPedido = $("#numeroPedido").val();
            var observacao = $("#observacao").val();
            
            if (observacao.trim() === '') {
                Swal.fire({
                    title: 'Atenção',
                    text: 'Por favor, digite uma observação',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            // Fazer requisição AJAX para salvar a observação
            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/atualizaTmpPedidoObservacao',
                data: {
                    'numeroPedido': numeroPedido,
                    'observacao': observacao
                },
                dataType: 'json',
                success: function (data) {
                    if (data != "") {
                        Swal.fire({
                            title: 'Sucesso',
                            text: 'Observação salva com sucesso!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("#modalObservacao").modal('hide');
                            }
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Erro',
                        text: 'Erro ao salvar observação',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        

        $(".excluir_Arquiv_full").on("click", function () {
            var arquivoFullId = $(this).attr('id');

            Swal.fire({
                title: "Tem certeza que deseja excluir?",
                text: "Isso removera o registro do arquivo importado",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sim, Apagar!",
                cancelButtonText: "Não, Cancelar!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'MercadoLivreIntegracao/excluirArquivoFull',
                        data: {
                            "arquivoFullId": arquivoFullId
                        },
                        async: false,
                        dataType: 'json',
                        success: function (data) {
                            if (data.resposta == "sucesso") {

                                window.location.href = '/MercadoLivreIntegracao/ArquivosFull?tipo_msg=sucesso&msg=Ação realizada!';
                            }
                        }
                    });
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


        var totalColunas = $('#dados_curva_abc thead th').length; // Conta o número total de colunas
        var indicePenultimaColuna = totalColunas - 2; // Calcula o índice da penúltima coluna

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
            order: [[indicePenultimaColuna, 'asc']],
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
                        dados_curva_abc.rows().deselect();
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
            scrollX: true,
            fixedColumns: {
                leftColumns: 2
            },
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
                { extend: 'colvis', className: 'botao_export', }
            ],
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



                        if (novo_valor < preco_antigo) {

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
                            title: '',
                            html: '<strong> <h2> Enviando informações. </br> Isso pode levar alguns minutos, avisaremos quando finalizar! <br><br> Enquanto isso, que tal tomar um cafézinho <i style="color: #783838;" class="icon-xl fas fa-coffee"></i></h2  ></strong>',
                            showCloseButton: false,
                            showCancelButton: false,
                            focusConfirm: false,
                            didOpen: () => { Swal.showLoading(); },
                            icon: "warning",
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

        // Impede a propagação do clique apenas para as divs, exceto o ícone de exclusão
        $('.dados_financeiro').on('click', function (event) {
            if (!$(event.target).is('a')) {
                event.stopPropagation(); // Impede que o clique se propague para a div_principal, exceto se for no link
            }
        });

        $("#btn_import_xml").click(function () {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success marginL10",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "Atenção",
                text: "Deseja limpar a tabela antes da importação?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sim!",
                cancelButtonText: "Não!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    swalWithBootstrapButtons.fire({
                        title: "Confirmado!",
                        text: "A tabela será limpa e a importação começará em breve!",
                        icon: "success"
                    });

                    $('#limpar_tabela').val("S")

                    setTimeout(() => {
                        $('#frm_import').submit();
                    }, 500);
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire({
                        title: "Confirmado!",
                        text: "A tabela não será limpa e a importação irá atualizar produtos ja existentes e inserir os novos!",
                        icon: "success"
                    });

                    $('#limpar_tabela').val("N")

                    setTimeout(() => {
                        $('#frm_import').submit();
                    }, 500);
                }
            });

        })


        $("#campo_pesquisa").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $(".div_principal").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });

            $(".div_principalHr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });

        });

        $(".input_qtd_pedido").blur(function () {


            //$(this).parent().parent().toggleClass('selected');
            var id = $(this).attr('id')
            id = id.split("_");
            var valor = $(this).val()


            if (parseInt(valor) > 0) {
                var x = 0;
                var valor_acumulado = 0;
                var valores_acumulados_array = [];
                var estoque_erp_padrao = $('.span_estoque_erp_' + id[1]).html();
                $('.div_principal').each(function () {
                    if ($(this).find('#input_' + id[1]).val() != undefined) {
                        var valor_novo = $(this).find('#input_' + id[1]).val();
                        valor_novo = parseInt(valor_novo)
                        valor_acumulado += valor_novo
                        valores_acumulados_array.push(valor_novo)
                    }

                    x++;
                });

                var indice = 0;
                $('.div_principal').each(function () {
                    if ($(this).find('#input_' + id[1]).length > 0) { // Verifica se o input existe

                        var estpque_erp_atual = $(this).find('.span_estoque_erp_' + id[1]).html();

                        var valor_novo = $(this).find('#input_' + id[1]).val()
                        valor_novo = parseInt(valor_novo)
                        if (!isNaN(valor_novo)) {



                            var estpque_erp_atual = $(this).find('.span_estoque_erp_' + id[1]).html();
                            if (estpque_erp_atual != estoque_erp_padrao) {
                                indice++;

                                var soma = valores_acumulados_array.slice(0, indice).reduce((acc, val) => acc + val, 0);

                                //estpque_erp_atual = parseInt(estpque_erp_atual);
                                $(this).find('.span_estoque_erp_' + id[1]).html(estoque_erp_padrao - soma)
                            }
                        }

                    }

                    x++;
                });
            }


        })

        $("#validar_gtin").on('click', function () {

            var valor = $("#gtin_conferir").val()
            var gtin_atual = $("#gtin_atual").val()


            if (valor != "") {
                if (valor != gtin_atual) {
                    Swal.fire({
                        title: 'Atenção',
                        text: "OS GTINS não estão em conformidade!",
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.close()
                            $("#gtin_conferir").val()
                        }
                    })
                } else {
                    Swal.fire({
                        title: 'Sucesso',
                        text: "OS GTINS estão em conformidade!",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.close()
                            $("#gtin_conferir").val()
                            $("#modalValidarGtin").modal("hide");
                        }
                    })
                }
            }


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
                $('.div_principal').each(function () {
                    if ($(this).find('.input_qtd_pedido').val() != "0" && $(this).find('.input_qtd_pedido').val() != "") {

                        dados_pedido[x] = {
                            "preco_custo": $(this).find('.input_preco_original').val().trim(),
                            "qtd_pedido": $(this).find('.input_qtd_pedido').val().trim(),
                            "sku_produto": $(this).find('.sku_produto').html().trim(),
                            "descricao_completa_produto": $(this).find('.descricao_completa_produto').html().trim(),
                            "id_produto_bling": $(this).find('.id_produto_bling').val().trim()
                        }
                    }

                    x++;
                });


                if (dados_pedido.length > 0) {

                    $.ajax({
                        type: "POST",
                        url: base_url + '/Integracao/criaPedidoCompra',
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
                            } else {
                                Swal.fire({
                                    title: 'Erro',
                                    text: "Ocorreu algum erro ao realizar o pedido de compra!",
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
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Erro',
                        text: "Ocorreu algum erro ao realizar o pedido de compra! Nenhum item possui quantidade determinada.",
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
                title: '',
                html: '<strong> <h2> Filtrando dados. </br> Isso pode levar alguns segundos!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                didOpen: () => { Swal.showLoading(); },
                icon: "warning",
            })

            $('#frm_pesquisa').submit();

            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguardar só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 50000);

            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguardar só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 90000);




        });


        $(".secao_dados_ml").hide();

        $("#xml_usado").on('change', function () {

            $.ajax({
                type: "POST",
                url: base_url + '/Integracao/getXMLItens',
                data: {
                    'id_xml': $(this).val(),
                },
                dataType: 'json',
                success: function (data) {
                    // Seleciona o corpo da tabela usando o ID
                    let tableBody = $("#tabela_item_xml tbody");

                    // Limpa o conteúdo atual da tabela (caso já tenha dados)
                    tableBody.empty();



                    // Itera sobre os dados recebidos e cria uma linha para cada item
                    data.forEach(item => {
                        let conferidoText = item.conferido ? '<img style="width: 30px; height: 30px; margin-left: 10px;" src="/template/images/checked.png" alt=""><label style="margin-left: 5px;" >' : '<a href="/Integracao/itemConferidoXml/' + item.id_bling_xml_precificacao_item + '"> <img  style="width: 30px; height: 30px; margin-left: 10px;" src="/template/images/clock.png" alt=""><label style="margin-left: 5px;" >';

                        let row = `
                            <tr>
                                <td>${item.cod_fornecedor}</td>
                                <td>R$ ${item.custo}</td>
                                <td>R$ ${item.ipi}</td>
                                <td>${item.porcentagem_ipi}%</td>
                                <td>R$ ${item.icms}</td>
                                <td>${item.porcentagem_icms}%</td>
                                <td>${item.qtd_comprada}</td>
                                <td>${conferidoText}</td> <!-- Coluna adicional para 'conferido' -->
                            </tr>
                        `;

                        // Adiciona a linha à tabela
                        tableBody.append(row);
                    });

                }
            });
        })

        $("#tabela_item_xml tbody").on("click", "td:first-child", function () {
            // Obtém o texto da célula clicada
            let valor = $(this).text();

            $("#codigo_fornecedor").val(valor).trigger('blur')
            $("#detalhes_nf").trigger('click')

        });


        $("#multi_conta_ml").on('change', function () {

            $.ajax({
                type: "POST",
                url: base_url + '/Integracao/getDadosContaMl',
                data: {
                    'id_mercado_livre': $(this).val(),
                },
                dataType: 'json',
                success: function (data) {

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

        $('.cadastrar_tabela_frete_ml').click(function () {
            var id = $(this).attr('id');

            var de = $("#de_ml_" + id).val();
            var ate = $("#ate_ml_" + id).val();
            var valor = $("#valor_ml_" + id).val();





            $.ajax({
                type: "POST",
                url: base_url + '/Integracao/cadastrarFreteMl',
                data: {
                    'id': id,
                    'de': de,
                    'ate': ate,
                    'valor': valor,
                    'tipo': "ml"
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
                                $("#de_ml_" + id).val('');
                                $("#ate_ml_" + id).val('');
                                $("#valor_ml_" + id).val('');
                                var fretes = data.fretes_cadastrados;
                                console.log(fretes);
                                $('.div_fretes_cadastrados_ml_'+id).html('');
                                for(var i = 0; i < fretes.length; i++){
                                    var string_frete = '<div class="col-md-12"  style="display: flex; flex-direction: row;" ><h4 style="margin-right: 10px" >De '+fretes[i].de+'Kg Até '+fretes[i].ate+'Kg -> R$ '+fretes[i].valor+'</h4> <a href="/Integracao/excluirFrete/'+fretes[i].id_bling_tabela_frete_intervalos    +'"><i style="color: #b30000; font-size: 20px!important" class="fa fa-trash"></i></a></div>'
                                    $('.div_fretes_cadastrados_ml_'+id).append(string_frete);
                                }
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
        });

        $('.cadastrar_tabela_frete').click(function () {
            var id = $(this).attr('id');

            var de = $("#de_" + id).val();
            var ate = $("#ate_" + id).val();
            var valor = $("#valor_" + id).val();





            $.ajax({
                type: "POST",
                url: base_url + '/Integracao/cadastrarFrete',
                data: {
                    'de': de,
                    'ate': ate,
                    'valor': valor,
                    'tipo': id
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
        });

        $('.cadastrar_tabela_embalagem').click(function () {

            var altura = $("#altura").val();
            var largura = $("#largura").val();
            var profundidade = $("#profundidade").val();
            var valor_embalagem = $("#valor_embalagem").val();
            var identificacao = $("#identificacao").val();


            $.ajax({
                type: "POST",
                url: base_url + '/Integracao/cadastrarEmbalagem',
                data: {
                    'altura': altura,
                    'largura': largura,
                    'profundidade': profundidade,
                    'valor_embalagem': valor_embalagem,
                    'identificacao': identificacao
                },
                dataType: 'json',
                success: function (data) {
                    if (data.msg == "sucesso") {
                        // Criar o novo elemento HTML
                        let novoElemento = `
                            <div class="col-md-12" style="display: flex;">
                                <h4 style="margin-right: 10px;">${altura}X${largura}X${profundidade} -> ${identificacao} (R$ ${valor_embalagem})</h4>
                                <a class="escolher_embalagem" id="${valor_embalagem}-${altura}-${largura}-${profundidade}">
                                    <i style="color:rgb(33, 161, 28); font-size: 20px!important; margin-right: 10px" class="fa fa-check"></i>
                                </a>
                                <a href="/Integracao/excluirEmabalagem/${data.insert_id}">
                                    <i style="color: #b30000; font-size: 20px!important" class="fa fa-trash"></i>
                                </a>
                            </div>
                        `;

                        // Inserir o novo elemento na div existente
                        $('.linhaEmbalagens').append(novoElemento);

                        // Exibir mensagem de sucesso
                        Swal.fire({
                            title: 'Sucesso',
                            text: "Dados atualizados com sucesso",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        });

                        $("#altura").val("");
                        $("#largura").val("");
                        $("#profundidade").val("");
                        $("#valor_embalagem").val("");
                        $("#identificacao").val("");
                    } else {
                        // Exibir mensagem de erro
                        Swal.fire({
                            title: 'Erro',
                            text: "Houve algum erro ao realizar a ação",
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#bf0f0f',
                            confirmButtonText: 'OK!'
                        });
                    }
                }
            });

        });

        $('.escolher_embalagem').click(function () {

            var valor = $(this).attr('id');
            valor = valor.split("-")
            $("#embalagem").val(valor[0])
            $("#embalagem_div").html("R$ " + valor[0])
            $("#largura_produto_div").html(valor[1])
            $("#altura_produto_div").html(valor[2])
            $("#profundidade_produto_div").html(valor[3])

            var peso_cubico = (valor[1] * valor[2] * valor[3]) / 6000;

            // Atualiza o valor do peso cúbico no div correspondente
            $("#peso_cubico_produto_div").html(peso_cubico.toFixed(2) + " Kg");

            $('.modal').modal('toggle');


        });

        $("#valor_ipi").blur(function () {
            var valor_ipi = $(this).val().replace("%", "") / 100;
            var valor_nf = $("#valor_nf").val().replace("R$ ", "").replace(",", ".");
            var nota_ipi = (parseFloat(valor_nf) * valor_ipi) + parseFloat(valor_nf);

            var valor_sem_nf_div = $("#valor_sem_nf_div").val().replace("%", "") / 100;
            var valor_sem_ipi = (parseFloat(valor_nf) * valor_sem_nf_div) - parseFloat(valor_nf);

            $("#valor_nota_ipi").html("R$ " + nota_ipi.toFixed(2))
            $("#total_sem_ipi").html("R$ " + valor_sem_ipi.toFixed(2))
            verificarEspecial()

        })

        $("#valor_sem_nf").blur(function () {
            var valor_sem_nf = $(this).val().replace("%", "") / 100;
            var valor_nf = $("#valor_nf").val().replace("R$ ", "").replace(",", ".");
            var valor_sem_ipi = parseFloat(valor_nf) - (parseFloat(valor_nf) * valor_sem_nf);

            $("#total_sem_ipi_div").html("R$ " + valor_sem_ipi.toFixed(2))
            verificarEspecial()

        })

        $("#custo_ultima_compra, #custo_compra_atual, #qtd_comprado, #qtd_compra_atual, #estoque_atual").blur(function () {
            var custo_ultima_compra = $("#custo_ultima_compra").val().replace("R$ ", "").replace(",", ".");
            var custo_compra_atual = $("#custo_compra_atual").val().replace("R$ ", "").replace(",", ".");
            var qtd_comprado = $("#qtd_comprado").val();
            var qtd_compra_atual = $("#qtd_compra_atual").val();
            var estoque_atual = $("#estoque_atual").val();

            console.log(custo_ultima_compra);
            console.log(qtd_comprado);
            console.log(custo_compra_atual);
            console.log(qtd_compra_atual);
            console.log(estoque_atual);

            var custo_final_medio_tabela = ((parseFloat(custo_ultima_compra) * qtd_comprado) + (parseFloat(custo_compra_atual) * qtd_compra_atual)) / estoque_atual;
            console.log(custo_final_medio_tabela);
            if (!isNaN(custo_final_medio_tabela) && custo_final_medio_tabela != Infinity) {
                $("#custo_final_medio_tabela").html("R$ " + custo_final_medio_tabela.toFixed(2)); // Formata o número para duas casas decimais
            }

        })

        function verificarEspecial() {
            // Função para limpar e converter valores para números
            function parseCurrency(value) {
                if (!value) return 0; // Se o valor for vazio ou indefinido, retorna 0
                return parseFloat(value.replace("R$", "").replace("%", "").replace(",", "").trim()) || 0; // Tenta converter, fallback para 0
            }

            var valor_sem_nf = parseCurrency($("#valor_sem_nf").val());
            var valor_nf = $("#valor_nf").val().replace("R$ ", "").replace(",", ".");
            var total_sem_ipi_div = parseCurrency($("#total_sem_ipi_div").html());

            var total_especial;
            if (valor_sem_nf > 50) {
                total_especial = total_sem_ipi_div - valor_nf;
            } else {
                total_especial = valor_nf - total_sem_ipi_div;
            }

            $("#total_especial").html("R$ " + total_especial.toFixed(2)); // Formata o número para duas casas decimais
            if (total_especial != null) {
                var valor_nota_ipi = $("#valor_nota_ipi").html().replace("R$ ", "").replace(",", "")
                var custo_final_tabela = total_especial + parseFloat(valor_nota_ipi)
                $("#custo_final_tabela").html("R$ " + custo_final_tabela.toFixed(2)); // Formata o número para duas casas decimais

            }


        }

        $(".alteracao_ml").blur(function () {
            var comissao_marketplace_ml = $("#comissao_marketplace_ml").val().replace("%", "");
            var imposto_ml = $("#imposto_ml").val().replace("%", "");
            var lucro_ml = $("#lucro_ml").val().replace("%", "");

            $("#total_taxas_ml").val(parseFloat(comissao_marketplace_ml) + parseFloat(imposto_ml) + parseFloat(lucro_ml))
            $("#total_taxas_ml_visivel").val(parseFloat(comissao_marketplace_ml) + parseFloat(imposto_ml) + parseFloat(lucro_ml) + "%")
        })

        $(".alteracao_ml_classico").blur(function () {
            var comissao_marketplace_ml_classico = $("#comissao_marketplace_ml_classico").val().replace("%", "");
            var imposto_ml_classico = $("#imposto_ml_classico").val().replace("%", "");
            var lucro_ml_classico = $("#lucro_ml_classico").val().replace("%", "");

            $("#total_taxas_ml_classico").val(parseFloat(comissao_marketplace_ml_classico) + parseFloat(imposto_ml_classico) + parseFloat(lucro_ml_classico))
            $("#total_taxas_ml_classico_visivel").val(parseFloat(comissao_marketplace_ml_classico) + parseFloat(imposto_ml_classico) + parseFloat(lucro_ml_classico) + "%")
        })

        $(".alteracao_ml_full").blur(function () {
            var comissao_marketplace_ml_full = $("#comissao_marketplace_ml_full").val().replace("%", "");
            var imposto_ml_full = $("#imposto_ml_full").val().replace("%", "");
            var lucro_ml_full = $("#lucro_ml_full").val().replace("%", "");

            $("#total_taxas_ml_full").val(parseFloat(comissao_marketplace_ml_full) + parseFloat(imposto_ml_full) + parseFloat(lucro_ml_full))
            $("#total_taxas_ml_full_visivel").val(parseFloat(comissao_marketplace_ml_full) + parseFloat(imposto_ml_full) + parseFloat(lucro_ml_full) + "%")
        })

        $(".alteracao_sp").blur(function () {
            var comissao_marketplace_sp = $("#comissao_marketplace_sp").val().replace("%", "");
            var imposto_sp = $("#imposto_sp").val().replace("%", "");
            var lucro_sp = $("#lucro_sp").val().replace("%", "");

            $("#total_taxas_sp").val(parseFloat(comissao_marketplace_sp) + parseFloat(imposto_sp) + parseFloat(lucro_sp))
            $("#total_taxas_sp_visivel").val(parseFloat(comissao_marketplace_sp) + parseFloat(imposto_sp) + parseFloat(lucro_sp) + "%")
        })

        $(".alteracao_mg").blur(function () {
            var comissao_marketplace_mg = $("#comissao_marketplace_mg").val().replace("%", "");
            var imposto_mg = $("#imposto_mg").val().replace("%", "");
            var lucro_mg = $("#lucro_mg").val().replace("%", "");

            $("#total_taxas_mg").val(parseFloat(comissao_marketplace_mg) + parseFloat(imposto_mg) + parseFloat(lucro_mg))
            $("#total_taxas_mg_visivel").val(parseFloat(comissao_marketplace_mg) + parseFloat(imposto_mg) + parseFloat(lucro_mg) + "%")
        })

        $(".alteracao_ym").blur(function () {
            var comissao_marketplace_ym = $("#comissao_marketplace_ym").val().replace("%", "");
            var imposto_ym = $("#imposto_ym").val().replace("%", "");
            var lucro_ym = $("#lucro_ym").val().replace("%", "");

            $("#total_taxas_ym").val(parseFloat(comissao_marketplace_ym) + parseFloat(imposto_ym) + parseFloat(lucro_ym))
            $("#total_taxas_ym_visivel").val(parseFloat(comissao_marketplace_ym) + parseFloat(imposto_ym) + parseFloat(lucro_ym) + "%")
        })

        $(".alteracao_p1").blur(function () {
            var comissao_marketplace_p1 = $("#comissao_marketplace_p1").val().replace("%", "");
            var imposto_p1 = $("#imposto_p1").val().replace("%", "");
            var lucro_p1 = $("#lucro_p1").val().replace("%", "");

            $("#total_taxas_p1").val(parseFloat(comissao_marketplace_p1) + parseFloat(imposto_p1) + parseFloat(lucro_p1))
            $("#total_taxas_p1_visivel").val(parseFloat(comissao_marketplace_p1) + parseFloat(imposto_p1) + parseFloat(lucro_p1) + "%")
        })

        $(".alteracao_p2").blur(function () {
            var comissao_marketplace_p2 = $("#comissao_marketplace_p2").val().replace("%", "");
            var imposto_p2 = $("#imposto_p2").val().replace("%", "");
            var lucro_p2 = $("#lucro_p2").val().replace("%", "");

            $("#total_taxas_p2").val(parseFloat(comissao_marketplace_p2) + parseFloat(imposto_p2) + parseFloat(lucro_p2))
            $("#total_taxas_p2_visivel").val(parseFloat(comissao_marketplace_p2) + parseFloat(imposto_p2) + parseFloat(lucro_p2) + "%")
        })

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
                title: '',
                html: '<strong> <h2> Importando anuncios. </br> Isso pode levar alguns minutos, avisaremos quando finalizar! <br><br> Enquanto isso, que tal tomar um cafézinho <i style="color: #783838;" class="icon-xl fas fa-coffee"></i></h2  ></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                didOpen: () => { Swal.showLoading(); },
                icon: "warning",
            })

            setTimeout(() => {
                $('#frm_import').submit();
            }, 500);

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

        if ($("#nova_conexao").val() == "S") {
            var id_conta_ml = $("#id_conta_ml").val()

            Swal.fire({
                title: '',
                html: '<strong> <h2> Sincronizando vendas. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2  ></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                didOpen: () => { Swal.showLoading(); },
                icon: "warning",
            })

            $('#frm_pesquisa').submit();

            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguardar só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 50000);

            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguardar só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
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

        $("#sincronizar_bling").click(function () {
            Swal.fire({
                title: '',
                html: '<strong> <h2> Sincronizando dados. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                didOpen: () => { Swal.showLoading(); },
                icon: "warning",
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
                    title: '',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguardar só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 50000);

            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguardar só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 90000);
        })

        $("#sincronizar_produtos_recentes").click(function () {
            Swal.fire({
                title: '',
                html: '<strong> <h2> Sincronizando dados. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                didOpen: () => { Swal.showLoading(); },
                icon: "warning",
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
                    title: '',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 50000);

            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 90000);
        })

        $("#sincronizar_vendas_recentes").click(function () {
            Swal.fire({
                title: '',
                html: '<strong> <h2> Sincronizando dados. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                didOpen: () => { Swal.showLoading(); },
                icon: "warning",
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
                    title: '',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 50000);

            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 90000);
        })

        const codigosProduto = JSON.parse($('#codigos_produto').val() || '[]');


        // Percorrer o array
        $.each(codigosProduto, function (index, codigo) {

            $.ajax({
                type: "POST",
                url: '/Integracao/getDataultimaVenda',
                data: {
                    'codigo': codigo
                },
                dataType: 'json',
                success: function (data) {
                    if (data != "") {
                        $(".span_ultima_venda" + index).html("")
                        $(".span_ultima_venda" + index).append("<span>" + inverterData(data) + "</span>")
                    }
                }
            });

        });


        function inverterData(data) {
            if (data.includes("/")) {
                return data.split("/").reverse().join("-");
            } else if (data.includes("-")) {
                return data.split("-").reverse().join("/");
            }
        }

        $("#sincronizar_pedidos_recentes").click(function () {
            Swal.fire({
                title: '',
                html: '<strong> <h2> Sincronizando dados. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                didOpen: () => { Swal.showLoading(); },
                icon: "warning",
            })
            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/getPedidosRecentes',
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
                                window.location.replace(base_url + "Integracao/integracaoBling?tipo_msg=sucesso&msg=Dados sincronizados!");
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
                                window.location.replace(base_url + "Integracao/integracaoBling?tipo_msg=sucesso&msg=Nenhum dado encontrado!");
                            }
                        })
                    }

                }
            });
            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 50000);

            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 90000);
        })

        $("#sincronizar_pedidos_separacao").click(function () {
            Swal.fire({
                title: '',
                html: '<strong> <h2> Sincronizando dados. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                didOpen: () => { Swal.showLoading(); },
                icon: "warning",
            })
            $.ajax({
                type: "POST",
                url: base_url + 'Integracao/getPedidosSeparacao',
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
                                window.location.replace(base_url + "Integracao/blingSeparacao?tipo_msg=sucesso&msg=Dados sincronizados!");
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
                                window.location.replace(base_url + "Integracao/blingSeparacao?tipo_msg=sucesso&msg=Nenhum dado encontrado!");
                            }
                        })
                    }

                }
            });
            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 50000);

            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 90000);
        })

        $("#sincronizar_dados_full").click(function () {
            Swal.fire({
                title: '',
                html: '<strong> <h2> Sincronizando dados. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                didOpen: () => { Swal.showLoading(); },
                icon: "warning",
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

            });

            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
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
                title: '',
                html: '<strong> <h2> Sincronizando dados. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                didOpen: () => { Swal.showLoading(); },
                icon: "warning",
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
                    title: '',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 50000);

            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguarde só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 90000);
        })


        $("#atualizar_filtro").click(function () {

            Swal.fire({
                title: '',
                html: '<strong> <h2> Sincronizando dados. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2  ></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                didOpen: () => { Swal.showLoading(); },
                icon: "warning",
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
                title: '',
                html: '<strong> <h2> Sincronizando dados. </br> Isso pode levar alguns minutos, avisaremos quando finalizar!</h2  ></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                didOpen: () => { Swal.showLoading(); },
                icon: "warning",
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
                                window.location.replace(base_url + "Integracao/blingSeparacao?tipo_msg=sucesso&msg=Ação realizada!");
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
                                window.location.replace(base_url + "Integracao/blingSeparacao?tipo_msg=sucesso&msg=Ação realizada!");
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


        function formatCurrency() {
            // Seleciona todas as divs ou outros elementos que contenham "R$"
            $("div:contains('R$ ')").each(function () {
                // Obtém o texto do elemento
                const text = $(this).text().trim();

                // Verifica se o texto começa com "R$ "
                if (text.startsWith("R$ ")) {
                    // Remove "R$ " e converte para número
                    const numericValue = text.replace("R$ ", "");

                    // Formata o número no estilo brasileiro
                    const formattedValue = parseFloat(numericValue.replace(",", "."))
                        .toLocaleString("pt-BR", { style: "currency", currency: "BRL" });

                    // Atualiza o texto do elemento
                    $(this).text(formattedValue);
                }
            });
        }

        function atualizaTotal(valor) {
            var valor_mmkt = parseFloat(valor)
            var valor_atual = $(".total_checkout").html()

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

        $('#envia_form').click(function () {

            Swal.fire({
                title: '',
                html: '<strong> <h2> Filtrando dados. </br> Isso pode levar alguns segundos!</h2></strong>',
                showCloseButton: false,
                showCancelButton: false,
                focusConfirm: false,
                didOpen: () => { Swal.showLoading(); },
                icon: "warning",
            })

            $('#frm_pesquisa').submit();

            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Ainda estamos buscando as informações. </br> Aguardar só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 50000);

            setTimeout(() => {
                Swal.fire({
                    title: '',
                    html: '<strong> <h2> Estamos quase lá. </br> Aguardar só mais alguns instantes!</h2></strong>',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    didOpen: () => { Swal.showLoading(); },
                    icon: "warning",
                })

            }, 90000);




        });

        // Funcionalidade de seleção de linhas na página de separação
        $(document).on('click', '.div_principal', function() {
            // Se a linha já está selecionada, remove a seleção
            if ($(this).hasClass('selecionada')) {
                $(this).removeClass('selecionada');
            } else {
                // Se não está selecionada, adiciona a seleção
                $(this).addClass('selecionada');
            }
        });

        // Funcionalidade de reload automático ao selecionar arquivo
        $(document).on('change', '#arquivo_selecionado', function() {
            var arquivoSelecionado = $(this).val();
            if (arquivoSelecionado) {
                window.location.href = '/MercadoLivreIntegracao/GestaoEstoque?arquivo=' + arquivoSelecionado;
            }
        });

        // Função para manter o parâmetro do arquivo na URL
        function manterArquivoNaUrl(urlBase) {
            var urlAtual = window.location.href;
            var arquivoParam = '';
            if (urlAtual.includes('arquivo=')) {
                arquivoParam = '&arquivo=' + urlAtual.split('arquivo=')[1].split('&')[0];
            }
            return urlBase + arquivoParam;
        }

    });


}); // End of use strict
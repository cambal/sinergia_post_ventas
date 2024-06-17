$(document).ready(function () {
    $('#preload').fadeOut(1000);
    listarCompra();
});

document.addEventListener("DOMContentLoaded", function () {
    $(".separadorMiles").on({
        "focus": function (event) {
            $(event.target).select();
        },
        "keyup": function (event) {
            $(event.target).val(function (index, value) {
                return value.replace(/\D/g, "")
                    .replace(/([0-9])([0-9]{0})$/, '$1')
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
            });
        }
    });

    $("#accion").prop("checked", true);
    $("#menudeo").prop("checked", false);
    $("#menudeo_menudeo").prop("checked", false);

    $('#tbl').DataTable({
        pageLength: 5,
        processing: true,
        paging: true,
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        "order": [
            [0, "desc"]
        ],
    });
    // 
    $(".confirmar").submit(function (e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Esta seguro de eliminar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI, Eliminar!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        })
    })
    $("#nom_cliente").autocomplete({
        minLength: 1,
        source: function (request, response) {
            $.ajax({
                url: "ajax.php",
                dataType: "json",
                data: {
                    q: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        select: function (event, ui) {
            $("#idcliente").val(ui.item.id);
            $("#cedulaNit").val(ui.item.cedulaNit);
            $("#nombre").val(ui.item.nombre);
            $("#nom_cliente").val(ui.item.label);
            $("#tel_cliente").val(ui.item.telefono);
            $("#dir_cliente").val(ui.item.direccion);
        }
    })
    $("#codigo1").autocomplete({
        minLength: 13,
        source: function (request, response) {
            $.ajax({
                url: "ajax.php",
                dataType: "json",
                data: {
                    compra: request.term
                },
                success: function (data) {
                    // console.log(data.length);
                    if (data.length > 0) {
                        Swal.fire({
                            position: 'center',
                            icon: 'info',
                            title: 'El código de barras ya existe',
                            showConfirmButton: false,
                            timer: 2000
                        })
                    }
                    // response(data);
                }
            }).fail(function (e) {
                console.log('Error!!' + JSON.stringify(e));
            });
        },
        select: function (event, ui) {
        }
    })
    $("#producto").autocomplete({
        minLength: 1,
        source: function (request, response) {
            $.ajax({
                url: "ajax.php",
                dataType: "json",
                data: {
                    pro: request.term
                },
                success: function (data) {
                    console.log(data);
                    $("#menudeo_venta").prop("checked", false);
                    $("#blister_venta").prop("checked", false);
                    $("#global_venta").prop("checked", false);
                    response(data);
                }
            });
        },
        select: function (event, ui) {
            console.log("llego");
            if (ui.item.precio_menudeo == 0 && ui.item.precio_blister > 0 && ui.item.precio_venta > 0) {
                console.log("blister y global");
                $("#blister_venta").prop("checked", true);
                $("#menudeo_venta").prop("disabled", true);
                $("#blister_venta").prop("disabled", false);
                $("#global_venta").prop("disabled", false);
            } else if (ui.item.precio_menudeo > 0 && ui.item.precio_blister > 0 && ui.item.precio_venta > 0) {
                console.log("menudeo blister y global");
                $("#menudeo_venta").prop("checked", true);
                $("#menudeo_venta").prop("disabled", false);
                $("#blister_venta").prop("disabled", false);
                $("#global_venta").prop("disabled", false);
            } else if (ui.item.precio_menudeo == 0 && ui.item.precio_blister == 0 && ui.item.precio_venta > 0) {
                console.log("global");
                $("#global_venta").prop("checked", true);
                $("#blister_venta").prop("disabled", true);
                $("#menudeo_venta").prop("disabled", true);
            } else if (ui.item.precio_menudeo > 0 && ui.item.precio_blister == 0 && ui.item.precio_venta > 0) {
                console.log("menudeo y global");
                $("#menudeo_venta").prop("checked", true);
                $("#menudeo_venta").prop("disabled", false);
                $("#blister_venta").prop("disabled", true);
                $("#global_venta").prop("disabled", false);
            }

            $("#cantidad").val('');
            $("#id").val(ui.item.id);
            $("#producto").val(ui.item.value);
            $("#cant_menudeo_venta").val(ui.item.cant_menudeo);
            $("#cant_blister_venta").val(ui.item.cant_blister);
            $("#cant_global_venta").val(ui.item.cant_global);
            $("#precio_menudeo").val(formatterPeso.format(ui.item.precio_menudeo));
            $("#precio_blister").val(formatterPeso.format(ui.item.precio_blister));
            $("#precio_venta").val(formatterPeso.format(ui.item.precio_venta));
            $("#existencia_venta").val(ui.item.existencia);
            $("#precioFormat").val(formatterPeso.format(ui.item.precio));
            $("#lote_venta").focus();
            consultarLote(ui.item.id, 'lote_venta');
        }
    });
    $("#producto_compra").autocomplete({
        minLength: 1,
        source: function (request, response) {
            $.ajax({
                url: "ajax.php",
                dataType: "json",
                data: {
                    compra: request.term
                },
                success: function (data) {
                    if (data.length == 0 || data == '') {
                        $("#precio_venta_compra").prop("disabled", false);
                        $("#menudeo_compra").prop("checked", false);
                        $("#blister_compra").prop("checked", false);
                        $("#porcentaje").prop("disabled", false);
                        $("#cant_global").prop("disabled", false);

                        $("#cant_menudeo").prop("disabled", true);
                        $("#cant_blister").prop("disabled", true);
                        // $("#cant_global").prop("disabled", true);

                        $("#precio_menudeo").prop("disabled", true);
                        $("#precio_blister").prop("disabled", true);
                        // $("#precio_venta_compra").prop("disabled", true);
                        // buttons
                        $("#btn_agregar").css("display", "block");
                        $("#btn_guardar").css("display", "none");
                        $(".form_hide").css("display", "none");
                        $(".form_hide_2").css("display", "block");
                        // hide elements
                        $("#laboratorio_compra").css("display", "none");
                        $("#tipo_compra").css("display", "none");
                        $("#laboratorio_compra_select").css("display", "block");
                        $("#tipo_compra_select").css("display", "block");
                        // disabled element
                        $('.form_control_disabled').prop('disabled', false);
                        // limpiar campos
                        $('#codigo_compra').val('');
                        $('#codigo_hijo_compra').val('');
                        $('#descripcion_compra').val('');
                        $('#invima_compra').val('');
                        $('#existencia_minima_compra').val('');
                        $('#tipo_compra_select').val('');
                        $('#laboratorio_compra_select').val('');
                        $('#precio_compra_compra').val('');
                        $('#precio_venta_compra').val('');
                        $('#lote_compra').val();
                        $('#vencimiento_compra').val('');
                    } else {
                        // buttons
                        $("#btn_agregar").css("display", "none");
                        $("#btn_guardar").css("display", "block");
                        $(".form_hide").css("display", "block");
                        $(".form_hide_2").css("display", "none");
                        // hide elements
                        $("#laboratorio_compra").css("display", "block");
                        $("#tipo_compra").css("display", "block");
                        $("#laboratorio_compra_select").css("display", "none");
                        $("#tipo_compra_select").css("display", "none");
                        // disabled element
                        $('.form_control_disabled').prop('disabled', true);
                        // 
                        $("#codigo_compra").focus();
                        response(data);
                    }
                }
            }).fail(function (e) {

                console.log('Error!!' + JSON.stringify(e));

            });
        },
        select: function (event, ui) {
            if (ui.item.precio_menudeo > 0) {
                $("#cant_global").prop("disabled", true);
                $('#precio_menudeo').prop('disabled', false);
                $('#porcentaje_menudeo').prop('disabled', false);
                // $('#cant_menudeo').prop('disabled', false);
                $("#menudeo_compra").prop("checked", false);
            } else {
                $('#precio_menudeo').prop('disabled', true);
                $('#porcentaje_menudeo').prop('disabled', true);
                // $('#cant_menudeo').prop('disabled', true);
                $("#menudeo_compra").prop("checked", true);
            }
            if (ui.item.precio_blister > 0) {
                $('#precio_blister').prop('disabled', false);
                $('#porcentaje_blister').prop('disabled', false);
                // $('#cant_blister').prop('disabled', false);
                $("#blister_compra").prop("checked", false);
            } else {
                $('#precio_blister').prop('disabled', true);
                $('#porcentaje_blister').prop('disabled', true);
                // $('#cant_blister').prop('disabled', true);
                $("#blister_compra").prop("checked", true);
            }
            $('#cant_global').prop('disabled', true);
            $('#menudeo_menudeo').prop('checked', false);
            $('#precio_venta_compra').prop('disabled', false);
            $('#porcentaje').prop('disabled', false);

            $(".form_hide").css("display", "block");
            $(".form_hide_2").css("display", "none");
            // hide elements
            $("#laboratorio_compra").css("display", "block");
            $("#tipo_compra").css("display", "block");
            $("#laboratorio_compra_select").css("display", "none");
            $("#tipo_compra_select").css("display", "none");
            // disabled element
            $('.form_control_disabled').prop('disabled', true);
            // 
            $("#id_compra").val(ui.item.id);
            $("#codigo_compra").val(ui.item.codigo);
            $("#codigo_hijo_compra").val(ui.item.codigo_hijo);
            $("#descripcion_compra").val(ui.item.descripcion);
            $("#invima_compra").val(ui.item.invima);
            $("#tipo_compra").val(ui.item.tipo);
            $(".presentacion_compra2").text(ui.item.presentacion);
            $("#laboratorio_compra").val(ui.item.laboratorio);
            $("#lote_compra").val('');
            $("#precio_menudeo").val(formatterPeso.format(ui.item.precio_menudeo));
            $("#cant_menudeo").val(ui.item.cant_menudeo);
            $("#precio_blister").val(formatterPeso.format(ui.item.precio_blister));
            $("#cant_blister").val(ui.item.cant_blister);
            $("#cant_global").val(ui.item.cant_global);
            $("#precio_compra_compra").val(formatterPeso.format(ui.item.precio_compra));
            $("#precio_venta_compra").val(formatterPeso.format(ui.item.precio));
            $("#cantidad_compra").val('');
            $("#existencia_minima_compra").val(ui.item.existencia_minima);
            $("#vencimiento_compra").val('');
            $("#cantidad_compra").focus();
            consultarLote(ui.item.id, 'lote_compra');
        }
    });
    $("#lote_compra").autocomplete({
        minLength: 1,
        source: function (request, response) {
            $.ajax({
                url: "ajax.php",
                dataType: "json",
                data: {
                    lote_compra: request.term,
                },
                success: function (data) {
                    console.log(data);
                    response(data);
                }
            }).fail(function (e) {
                console.log('Error!!' + JSON.stringify(e));
            });
        },
        select: function (event, ui) {
            // $("#buscar_lote").val(ui.item.lote);
        }
    });
    $("#entrada_admin").autocomplete({
        minLength: 1,
        source: function (request, response) {
            $.ajax({
                url: "ajax.php",
                dataType: "json",
                data: {
                    compra: request.term
                },
                success: function (data) {
                    response(data);
                }
            }).fail(function (e) {
                console.log('Error!!' + JSON.stringify(e));
            });
        },
        select: function (event, ui) {
            $("#id_compra").val(ui.item.id);
            $("#codigo_compra").val(ui.item.codigo);
            $("#codigo_hijo_compra").val(ui.item.codigo_hijo);
            $("#descripcion_compra").val(ui.item.descripcion);
            $("#invima_compra").val(ui.item.invima);
            $("#tipo_compra").val(ui.item.tipo);
            $(".presentacion_compra2").text(ui.item.presentacion);
            $("#laboratorio_compra").val(ui.item.laboratorio);
            $("#lote_compra").val('');
            $("#precio_menudeo").val(formatterPeso.format(ui.item.precio_menudeo));
            $("#cant_menudeo").val(ui.item.cant_menudeo);
            $("#precio_blister").val(formatterPeso.format(ui.item.precio_blister));
            $("#cant_blister").val(ui.item.cant_blister);
            $("#cant_global").val(ui.item.cant_global);
            $("#precio_compra_compra").val(formatterPeso.format(ui.item.precio_compra));
            $("#precio_venta_compra").val(formatterPeso.format(ui.item.precio));
            $("#cantidad_compra").val('');
            $("#existencia_minima_compra").val(ui.item.existencia_minima);
            $("#vencimiento_compra").val('');
            $("#cantidad_compra").focus();
            consultarLote(ui.item.id, 'lote_compra');
        }
    });
    function consultarLote(codigo, lote) {
        $.ajax({
            url: "ajax.php",
            dataType: "json",
            data: {
                id_prod: codigo,
                traerLote: 'traerLote'
            },
            success: function (response) {
                $('#' + lote).find('option').remove();
                var option = new Option('Selecciona un lote', '', true, true);
                $('#' + lote).append(option).trigger('change');
                for (let index = 0; index < response.length; index++) {
                    const element = response[index];
                    var option = new Option(element.label, element.lote, false, false);
                    $('#' + lote).append(option).trigger('change');
                }
                $("#lote_venta").focus();
            },
        }).fail(function (e) {
            console.log('Error!!' + JSON.stringify(e));
        })
    }
    $('#btn_generar').click(function (e) {
        e.preventDefault();
        $("#swal-input1").focus();
        var rows = $('#tblDetalle tr').length;
        if (rows > 2) {
            var id;
            if ($('#idcliente').val() == '' || $('#idcliente').val() == undefined || $('#idcliente').val() == null) {
                id = 0;
            } else {
                id = $('#idcliente').val();
            }

            // modal paraconfirmar precio
            $.ajax({
                url: 'ajax.php',
                async: true,
                data: {
                    consultarVenta: 'consultarVenta',
                    id: id
                },
                success: function (response) {
                    const res = JSON.parse(response);
                    console.log(res);
                    Swal.fire({
                        title: 'Total a pagar',
                        text: '23423',
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar venta',
                        confirmButtonText: 'Confirmar venta',
                        // allowOutsideClick: false,
                        html: `
                        $${formatterPeso.format(res)}
                        <div class="form-group mt-3">
                        <label class="form-label" for="swal-input1">
   Paga con:
  </label>
  <input class="form-control separadorMiles" type="number" id="swal-input1">
</div>
<div class="form-group">
  <label class="form-label" for="swal-input1">
Metodo de pago:
</label>
</div>
                        <div class="d-flex justify-content-center">
<div class="form-check d-flex justify-content-center align-items-center">
  <input class="form-check-input" type="radio" name="flexRadioDefault" id="efectivo" value="efectivo">
  <div class="d-flex justify-content-center align-items-center">
-
</div>
  <label class="form-check-label mx-0 pt-2" for="flexRadioDefault1">
   Efectivo
  </label>
</div>
<div class="d-flex justify-content-center align-items-center">
---
</div>
<div class="form-check d-flex justify-content-center align-items-center ml-5">
  <input class="form-check-input" type="radio" name="flexRadioDefault" id="nequi" value="nequi">
  <div class="d-flex justify-content-center align-items-center">
  -
  </div>
  <label class="form-check-label mx-0 pt-2" for="flexRadioDefault2">
   Nequi
  </label>
</div>
<div class="d-flex justify-content-center align-items-center">
---
</div>
<div class="form-check d-flex justify-content-center align-items-center ml-3">
  <input class="form-check-input" type="radio" name="flexRadioDefault" id="daviplata" value="daviplata">
  <div class="d-flex justify-content-center align-items-center">
  -
  </div>
  <label class="form-check-label mx-0 pt-2" for="flexRadioDefault3">
   Daviplata
  </label>
</div>
<div class="d-flex justify-content-center align-items-center">
---
</div>
<div class="form-check d-flex justify-content-center align-items-center ml-3">
  <input class="form-check-input" type="radio" name="flexRadioDefault" id="tarjeta" value="tarjeta">
  <div class="d-flex justify-content-center align-items-center">
  -
  </div>
  <label class="form-check-label mx-0 pt-2" for="flexRadioDefault4">
   Tarjeta
  </label>
</div>
</div>
                        `,
                        preConfirm: () => {
                            let result = '';
                            var value = $("input[type=radio][name=flexRadioDefault]:checked").val();
                            if (value) {
                                result = value;
                            }
                            if (result == '' || $('#swal-input1').val() == '' || $('#swal-input1').val() == null || $('#swal-input1').val() == undefined || $('#swal-input1').val() == 0) {
                                return Swal.showValidationMessage(
                                    `Debes llenar todos los campos`
                                )
                            } else {
                                Swal.fire({
                                    // title: "Venta exitosa",
                                    showDenyButton: true,
                                    confirmButtonText: "Imprimir",
                                    denyButtonText: `No imprimir`,
                                    html: `
                                    <div class="">
                                    <h6>Total a pagar: </h6>
                                    <span type="text" name="radio" class="ml-5">
                                    $${formatterPeso.format(res)}
                                    </span>
                                    </div>
                                    <div class="">
                                    <h6>Pagan con: </h6>
                                    <span type="text" name="radio" class="ml-5">
                                    $${formatterPeso.format($('#swal-input1').val())}
                                    </span>
                                    </div>
                                    </div>
                                    <div class="">
                                    <h6>Devolución: </h6>
                                    <span type="text" name="radio" class="ml-5">
                                    $${formatterPeso.format($('#swal-input1').val() - res)}
                                    </span>
                                    </div>
                                    `,
                                    icon: "success",
                                    // timer: 5000
                                }).then((res) => {
                                    /* Read more about isConfirmed, isDenied below */
                                    if (res.isConfirmed) {
                                        // Swal.fire("Saved!", "", "success");
                                        confirmar_compra_pdf(id, result);
                                    } else if (res.isDenied) {
                                        confirmar_compra(id, result);
                                    }
                                });
                            }
                        }
                    })
                }
            }).fail(function (e) {
                alert('Error!!' + JSON.stringify(e));
            });
        } else {
            Swal.fire({
                position: 'center',
                icon: 'warning',
                title: 'No hay producto para generar la venta',
                showConfirmButton: false,
                timer: 2000
            })
        }

    });
    function confirmar_compra(id, metodo_pago) {
        var action = 'procesarVenta';
        $.ajax({
            url: 'ajax.php',
            async: true,
            data: {
                procesarVenta: action,
                id: id,
                metodo_pago: metodo_pago
            },
            success: function (response) {
                console.log(response);
                const res = JSON.parse(response);
                console.log(res);
                if (response.mensaje == 'error') {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Error al generar la venta',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    location.reload();
                }
            }
        }).fail(function (e) {
            alert('Error!!' + JSON.stringify(e));
        });
    }
    function confirmar_compra_pdf(id, metodo_pago) {
        var action = 'procesarVenta';
        $.ajax({
            url: 'ajax.php',
            async: true,
            data: {
                procesarVenta: action,
                id: id,
                metodo_pago: metodo_pago
            },
            success: function (response) {
                const res = JSON.parse(response);
                console.log(res);
                if (response != 'error') {
                    setTimeout(() => {
                        generarPDF(res.id_cliente, res.id_venta);
                        location.reload();
                    }, 300);
                } else {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Error al generar la venta',
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
            }
        }).fail(function (e) {
            alert('Error!!' + JSON.stringify(e));
        });
    }
    $('#btn_generar_compra').click(function (e) {
        e.preventDefault();
        const proveedor_compra = $('#proveedor_compra').val();
        const num_fac_compra = $('#num_fac_compra').val();
        const total_fac_compra = $('#total_fac_compra').val().replace(/[$,]/g, '');

        if (proveedor_compra == '' || num_fac_compra == '' || total_fac_compra == '') {
            Swal.fire({
                position: 'center',
                icon: 'warning',
                title: 'Todos los datos de la factura deben estar llenos',
                showConfirmButton: false,
                timer: 2000
            })
            $('#num_fac_compra').focus();
            return;
        }
        var rows = $('#tblDetalleCompra tr').length;
        if (rows > 2) {
            // var filas = document.querySelectorAll("#tblDetalleCompra tfoot tr td");
            let numFormat = $("#total_pagar").val();

            var action = 'procesarCompra';
            if (numFormat != formatterPeso.format(total_fac_compra)) {
                Swal.fire({
                    position: 'center',
                    icon: 'warning',
                    text: 'El valor total de la factura debe coincidir con el valor total de los productos selecionados',
                    showConfirmButton: false,
                    timer: 2000
                });
                $('#total_fac_compra').focus();
                return;
            }
            $.ajax({
                url: "ajax.php",
                type: 'POST',
                // async: true,
                data: {
                    procesarCompra: action,
                    proveedor_compra: proveedor_compra,
                    num_fac_compra: num_fac_compra,
                    total_fac_compra: total_fac_compra
                },
                success: function (response) {
                    console.log(response);
                    const res = JSON.parse(response);
                    if (response != 'error') {
                        Swal.fire({
                            title: 'Compra generada ¿desea imprimir la factura?',
                            icon: 'success',
                            showDenyButton: true,
                            showCancelButton: false,
                            confirmButtonText: 'Imprimir',
                            denyButtonText: `No gracias`,
                            // confirmButtonColor: 
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                setTimeout(() => {
                                    generarPDFcompra(res.id_compra);
                                    location.reload();
                                }, 300);
                            } else if (result.isDenied) {
                                location.reload();
                            }
                        })
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Error al generar la compra',
                            showConfirmButton: false,
                            timer: 2000
                        })
                    }
                }
            });
        } else {
            Swal.fire({
                position: 'center',
                icon: 'warning',
                title: 'No hay producto para generar la compra',
                showConfirmButton: false,
                timer: 2000
            })
        }
    });

    $('#menudeo').on('change', function () {
        if ($(this).is(':checked')) {
            $('#precio_menudeo').prop('disabled', false);
            $('#cant_menudeo').prop('disabled', false);
            $('#cant_menudeo').val(0);
            $('#precio_menudeo').val(0);

        } else {
            $('#precio_menudeo').prop('disabled', true);
            $('#cant_menudeo').prop('disabled', true);
            $('#precio_menudeo').val(0);
            $('#cant_menudeo').val(0);
        }
    });

    $('#blister').on('change', function () {
        if ($(this).is(':checked')) {
            $('#precio_blister').prop('disabled', false);
            $('#cant_blister').val(0);
            $('#precio_blister').val(0);
            $('#cant_blister').prop('disabled', false);
        } else {
            $('#precio_blister').prop('disabled', true);
            $('#cant_blister').prop('disabled', true);
            $('#cant_blister').val(0);
            $('#precio_blister').val(0);
        }
    });
    // compra
    $('#menudeo_compra').on('change', function () {
        if ($(this).is(':checked')) {
            $('#precio_menudeo').prop('disabled', false);
            $('#cant_menudeo').prop('disabled', false);
            $('#porcentaje_menudeo').prop('disabled', false);
            $('#cant_menudeo').val('');
            $('#precio_menudeo').val('');

        } else {
            $('#precio_menudeo').prop('disabled', true);
            $('#cant_menudeo').prop('disabled', true);
            $('#porcentaje_menudeo').prop('disabled', true);
            $('#precio_menudeo').val('');
            $('#cant_menudeo').val('');
        }
    });
    // menudeo menudeo
    $('#menudeo_menudeo').on('change', function () {
        $("#cantidad_compra").val('');
        $("#cantidad_compra").focus();
    });

    $('#blister_compra').on('change', function () {
        if ($(this).is(':checked')) {
            $('#precio_blister').prop('disabled', false);
            $('#cant_blister').val('');
            $('#precio_blister').val('');
            $('#cant_blister').prop('disabled', false);
            $('#porcentaje_blister').prop('disabled', false);
        } else {
            $('#precio_blister').prop('disabled', true);
            $('#cant_blister').prop('disabled', true);
            $('#porcentaje_blister').prop('disabled', true);
            $('#cant_blister').val('');
            $('#precio_blister').val('');
        }
    });

    $('#openModal').click(function () {
        $("#menudeo").prop("checked", false);
        $("#blister").prop("checked", false);
        $('.vencimiento').remove();
        $('#listas').append('<div>\
            <input type="date" class="form-control vencimiento" id="vencimiento" name="campo[]">\
            </div>');
    });

    // ventas
    $('.form-check .form-check-input').on('change', function () {
        $("#cantidad").val('');
        $("#sub_total").val('');
    });


    if (document.getElementById("detalle_venta")) {
        listar();
    }
    if (document.getElementById("detalle_compra")) {
        listarCompra();
    }
    if (document.querySelector("#download_xlsx")) {
        let download_xlsx = document.querySelector("#download_xlsx")
        download_xlsx.addEventListener("click", () => {
            ExcellentExport.convert({ anchor: download_xlsx, filename: 'Sinergia_post_ventas', format: 'xlsx' }, [{ name: 'Sheet Name Here 1', from: { table: 'tbl' } }])
        })
    }
    if (document.querySelector("#download_xlsx_sugerido")) {
        let download_xlsx = document.querySelector("#download_xlsx_sugerido")
        download_xlsx.addEventListener("click", () => {
            ExcellentExport.convert({ anchor: download_xlsx, filename: 'Sinergia_post_ventas', format: 'xlsx' }, [{ name: 'Sheet Name Here 1', from: { table: 'tbl_sugerido' } }])
        })
    }
    if (document.querySelector("#download_xlsx_productos_utilidad")) {
        let download_xlsx = document.querySelector("#download_xlsx_productos_utilidad")
        download_xlsx.addEventListener("click", () => {
            ExcellentExport.convert({ anchor: download_xlsx, filename: 'Sinergia_post_ventas', format: 'xlsx' }, [{ name: 'Sheet Name Here 1', from: { table: 'tbl_productos_existentes' } }])
        })
    }
    if (document.querySelector("#download_xlsx_cierre_caja")) {
        let download_xlsx = document.querySelector("#download_xlsx_cierre_caja")
        download_xlsx.addEventListener("click", () => {
            ExcellentExport.convert({ anchor: download_xlsx, filename: 'Sinergia_post_ventas', format: 'xlsx' }, [{ name: 'Sheet Name Here 1', from: { table: 'tbl_cierre_caja' } }])
        })
    }
})
$('#c').click(function (e) {
});
const formatterPeso = new Intl.NumberFormat('en-US', {
    // style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 0
})
// calcular porcentaje venta
function onKeyPressBlockChars(e, numero) {
    // alert('sad')
    var key = window.event ? e.keyCode : e.which;
    var keychar = String.fromCharCode(key);
    reg = /\d|\./;
    if (numero.indexOf(".") != -1 && keychar == ".") {
        return false;
    } else {
        return reg.test(keychar);
    }
}
function entradaAdmin2() {
    const id = $('#id').val();
    const cantidad = $('#cantidad').val();
    const producto = $('#producto').val();

    if (cantidad == '' || cantidad == undefined || cantidad == null || producto == '' || producto == undefined || producto == null) {
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Los campos deben estar llenos',
            showConfirmButton: false,
            timer: 2000
        })
        return;
    }

    var cant_unidad;

    if ($('#menudeo_venta').prop('checked')) {
        cant_unidad = $("#cant_menudeo_venta").val() * cantidad;
    } else if ($('#blister_venta').prop('checked')) {
        cant_unidad = $("#cant_blister_venta").val() * cantidad;
    } else if ($('#global_venta').prop('checked')) {
        cant_unidad = $("#cant_global_venta").val() * cantidad;
    }

    $.ajax({
        url: "ajax.php",
        type: 'POST',
        dataType: "json",
        data: {
            id: id,
            cant_unidad: cant_unidad,
            cantidad: cantidad,
            entradaAdmin2: 'entradaAdmin2',
        },
        success: function (response) {
            console.log(response);
            if (response == 'ok') {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Salida realizada exitosamente',
                    showConfirmButton: false,
                    timer: 2000
                })
                location.reload();
            } else if (response == 'error') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Ha ocurrido un problema, vuleve a intentar',
                    showConfirmButton: false,
                    timer: 2000
                })
            } else if (response == 'no hay unidades') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'El producto no tiene esas unidades',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    }).fail(function (e) {
        console.log('Error!!' + JSON.stringify(e));
        if (e.responseText == 'ok') {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Salida realizada exitosamente',
                showConfirmButton: false,
                timer: 2000
            })
            location.reload();
        } else if (e.responseText == 'error') {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Ha ocurrido un problema, vuleve a intentar',
                showConfirmButton: false,
                timer: 2000
            })
        } else if (e.responseText == 'no hay unidades') {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'El producto no tiene esas unidades',
                showConfirmButton: false,
                timer: 2000
            })
        }
    })
}
function entradaAdmin(e) {
    e.preventDefault();
    // 
    const cantidad_compra = $('#cantidad_compra').val();
    const cant_menudeo = $('#cant_menudeo').val();
    const cant_blister = $('#cant_blister').val();
    const cant_global = $('#cant_global').val();
    // 
    const id_producto = $("#id_compra").val();
    const lote = $("#lote_compra").val();
    const vencimiento = $("#vencimiento_compra").val();
    let cantunitario = 0;
    // validando campos
    if (cantidad_compra == '' || lote == '' || vencimiento == '') {
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Todos los campos (*) deben estar llenos',
            showConfirmButton: false,
            timer: 2000
        });
        return;
    }

    // validando si es menudeo o no
    if ($('#menudeo_menudeo').prop('checked')) {
        // si es menudeo
        if (cant_menudeo == 0 && cant_blister == 0 && cant_global > 0) {
            console.log("entro solo unidad");
            cantunitario = cantidad_compra * cant_global;
        } else if (cant_menudeo == 0 && cant_blister > 0) {
            console.log("entro solo blister");
            cantunitario = cantidad_compra * cant_blister;
        } else if (cant_menudeo > 0 && cant_blister > 0 && cant_global > 0) {
            console.log("entro solo todo");
            cantunitario = cantidad_compra * cant_menudeo;
        } else if (cant_menudeo > 0 && cant_blister == 0 && cant_global > 0) {
            console.log("entro menudeo y todo");
            cantunitario = cantidad_compra * cant_menudeo;
        }
    } else {
        cantunitario = cantidad_compra * cant_global;
    }
    let action = 'entradaAdmin';
    $.ajax({
        url: "ajax.php",
        type: 'POST',
        data: {
            id_producto: id_producto,
            lote: lote,
            vencimiento: vencimiento,
            cantidad: cantidad_compra,
            cantidad_unidad: cantunitario,
            entradaAdmin: action,
        },
        success: function (response) {
            console.log(response);
            if (response.mensaje == 'error') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: response.mensaje,
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: response.mensaje,
                    showConfirmButton: false,
                    timer: 2000
                });
                // $('#form_producto_lote')[0].reset();
                // $("#entrada_admin").focus();
                location.reload();
            }

        }, error(e) {
            console.log(JSON.stringify(e));
        }
    });
}
function salidaAdmin() {
    const id = $('#id').val();
    const cantidad = $('#cantidad').val();
    const producto = $('#producto').val();
    const lote = $('#lote_venta').val();

    if (cantidad == '' || cantidad == undefined || cantidad == null || producto == '' || producto == undefined || producto == null || lote == '' || lote == undefined || lote == null) {
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Los campos deben estar llenos',
            showConfirmButton: false,
            timer: 2000
        })
        return;
    }

    var cant_unidad;

    if ($('#menudeo_venta').prop('checked')) {
        cant_unidad = $("#cant_menudeo_venta").val() * cantidad;
    } else if ($('#blister_venta').prop('checked')) {
        cant_unidad = $("#cant_blister_venta").val() * cantidad;
    } else if ($('#global_venta').prop('checked')) {
        cant_unidad = $("#cant_global_venta").val() * cantidad;
    }

    $.ajax({
        url: "ajax.php",
        type: 'POST',
        dataType: "json",
        data: {
            id: id,
            cant_unidad: cant_unidad,
            cantidad: cantidad,
            lote: lote,
            salidaAdmin: 'salidaAdmin',
        },
        success: function (response) {
            console.log(response);
            if (response == 'ok') {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Salida realizada exitosamente',
                    showConfirmButton: false,
                    timer: 2000
                })
                location.reload();
            } else if (response == 'error') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Ha ocurrido un problema, vuleve a intentar',
                    showConfirmButton: false,
                    timer: 2000
                })
            } else if (response == 'no hay unidades') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'El producto no tiene esas unidades',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    }).fail(function (e) {
        console.log('Error!!' + JSON.stringify(e));
        if (e.responseText == 'ok') {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Salida realizada exitosamente',
                showConfirmButton: false,
                timer: 2000
            })
            location.reload();
        } else if (e.responseText == 'error') {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Ha ocurrido un problema, vuleve a intentar',
                showConfirmButton: false,
                timer: 2000
            })
        } else if (e.responseText == 'no hay unidades') {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'El producto no tiene esas unidades',
                showConfirmButton: false,
                timer: 2000
            })
        }
    })
}
function calculaPorcentajes() {
    // variables
    const precio_compra_compra = $('#precio_compra_compra').val();
    const porcentaje = $('#porcentaje').val();
    // division precio compra con presentacion global para sacarlo por unidades
    const divi = precio_compra_compra;
    // sacando porcentaje
    const porc = Math.floor(precio_compra_compra * porcentaje) / 100;
    // dividiendo porcentaje con presentacion global para sacarlo por unidades
    const diviPor = porc
    // sumamos porcentaje masprecio compra por unidad
    const sum = parseFloat(diviPor) + parseFloat(divi);
    // guardamos el valor en el input
    document.getElementById("precio_venta_compra").value = sum;
}
function calculaPorcentajesMenudeo() {
    // variables
    const precio_compra_compra = $('#precio_compra_compra').val();
    const cant_global = $('#cant_global').val();
    const porcentaje = $('#porcentaje_menudeo').val();
    // division precio compra con presentacion global para sacarlo por unidades
    const divi = precio_compra_compra / cant_global;
    // sacando porcentaje
    const porc = Math.floor(divi * porcentaje) / 100;
    // dividiendo porcentaje con presentacion global para sacarlo por unidades
    const diviPor = porc
    // sumamos porcentaje masprecio compra por unidad
    const sum = parseFloat(diviPor) + parseFloat(divi);
    // guardamos el valor en el input
    document.getElementById("precio_menudeo").value = sum;
}
function calculaPorcentajesBlister() {
    // variables
    const precio_compra_compra = $('#precio_compra_compra').val();
    const cant_blister = $('#cant_blister').val();
    const cant_global = $('#cant_global').val();
    const porcentaje = $('#porcentaje_blister').val();
    // cant_blister * cant_global
    const divBandG = cant_global / cant_blister;
    const diviCandG = precio_compra_compra / divBandG;
    // division precio compra con presentacion global para sacarlo por unidades
    const divi = precio_compra_compra / cant_global;
    // sacando porcentaje
    const porc = Math.floor(diviCandG * porcentaje) / 100;
    // dividiendo porcentaje con presentacion global para sacarlo por unidades
    // console.log(porc);
    const diviPor = porc / cant_global;
    // sumamos porcentaje masprecio compra por unidad
    const sum = parseFloat(diviCandG) + parseFloat(porc);
    // guardamos el valor en el input
    document.getElementById("precio_blister").value = sum;
}
function guardarProducto() {
    // e.preventDefault();
    const codigo = $('#codigo_compra').val();
    const codigo_hijo_compra = $('#codigo_hijo_compra').val();
    const descripcion_compra = $('#descripcion_compra').val();
    const invima_compra = $('#invima_compra').val();
    const existencia_minima_compra = $('#existencia_minima_compra').val();
    const tipo_compra = $('#tipo_compra_select').val();
    const laboratorio_compra = $('#laboratorio_compra_select').val();

    const cant_menudeo = $('#cant_menudeo').val();
    const cant_blister = $('#cant_blister').val();
    const cant_global = $('#cant_global').val();

    const precio_menudeo = $('#precio_menudeo').val().replace(/[$,]/g, '');
    const precio_blister = $('#precio_blister').val().replace(/[$,]/g, '');

    const precio_compra_compra = $('#precio_compra_compra').val().replace(/[$,]/g, '');
    const precio_venta_compra = $('#precio_venta_compra').val().replace(/[$,]/g, '');

    let arrayData = [
        codigo, descripcion_compra, existencia_minima_compra, tipo_compra, laboratorio_compra, cant_global, precio_compra_compra, precio_venta_compra
    ];
    // recorriendo y validando campos obligatorios
    for (let index = 0; index < arrayData.length; index++) {
        const element = arrayData[index];
        if (element == '' || element == undefined) {
            Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Debes llenar todos los campos obligatorios (*)',
                showConfirmButton: false,
                timer: 2000
            })
            return;
        }
    }

    $.ajax({
        url: "ajax.php",
        type: 'POST',
        dataType: "json",
        data: {
            codigo: codigo,
            codigo_hijo_compra: codigo_hijo_compra,
            descripcion_compra: descripcion_compra,
            invima_compra: invima_compra,
            existencia_minima_compra: existencia_minima_compra,
            tipo_compra: tipo_compra,
            laboratorio_compra: laboratorio_compra,
            precio_compra_compra: precio_compra_compra,
            precio_venta_compra: precio_venta_compra,
            cant_menudeo: cant_menudeo,
            cant_blister: cant_blister,
            cant_global: cant_global,
            precio_menudeo: precio_menudeo,
            precio_blister: precio_blister,

            guardarPoducto: 'guardarPoducto'
        },
        success: function (response) {
            console.log(response);
            if (response == "producto guardado correctamente") {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: response,
                    showConfirmButton: false,
                    timer: 2000
                })
                $('#form_compras')[0].reset();
                // buttons
                $("#btn_agregar").css("display", "none");
                $("#btn_guardar").css("display", "block");
                $(".form_hide").css("display", "block");
                // hide elements
                $("#laboratorio_compra").css("display", "block");
                $("#tipo_compra").css("display", "block");
                $("#presentacion_compra").css("display", "block");
                $("#laboratorio_compra_select").css("display", "none");
                $("#tipo_compra_select").css("display", "none");
                $("#presentacion_compra_select").css("display", "none")
                // disabled element
                $('#codigo_compra').prop('disabled', true);
                $('#descripcion_compra').prop('disabled', true);
                $('#existencia_minima_compra').prop('disabled', true);
                $('#invima_compra').prop('disabled', true);
                $('#tipo_compra').prop('disabled', true);
                $('#laboratorio_compra').prop('disabled', true);
                $('#presentacion_compra').prop('disabled', true);
                $('#cant_presentacion').prop('disabled', true);
                $('#cant_global_presentacion').prop('disabled', true);
                $('#producto_compra').focus();
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'info',
                    title: response,
                    showConfirmButton: false,
                    timer: 3000
                })
            }
        }
    }).fail(function (e) {
        console.log('Error!!' + JSON.stringify(e));
    })
}
function guardarProductoBtn() {

    // e.preventDefault();
    const codigo = $('#codigo1').val();
    const codigo_hijo = $('#codigo_hijo').val();
    const descripcion_compra = $('#producto').val();
    const invima_compra = $('#invima').val();
    const existencia_minima_compra = $('#existencia_minima').val();
    const iva = $('#iva').val();
    const tipo_compra = $('#tipo').val();
    const laboratorio_compra = $('#laboratorio').val();

    const cant_menudeo = $('#cant_menudeo').val();
    const cant_blister = $('#cant_blister').val();
    const cant_global = $('#cant_global').val();

    const precio_menudeo = $('#precio_menudeo').val().replace(/[$,]/g, '');
    const precio_blister = $('#precio_blister').val().replace(/[$,]/g, '');

    const precio_compra_compra = $('#precio_compra').val().replace(/[$,]/g, '');
    const precio_venta_compra = $('#precio').val().replace(/[$,]/g, '');
    // const vencimiento_compra = document.querySelectorAll(".vencimiento");
    const cantidad = $('#cantidad').val();
    // array campos obligatorios
    let arrayData = [
        codigo, descripcion_compra, existencia_minima_compra, tipo_compra, laboratorio_compra, cant_global, precio_compra_compra, precio_venta_compra
    ];
    // recorriendo y validando campos obligatorios
    for (let index = 0; index < arrayData.length; index++) {
        const element = arrayData[index];
        if (element == '' || element == undefined) {
            Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Debes llenar todos los campos obligatorios (*)',
                showConfirmButton: false,
                timer: 2000
            })
            return;
        }
    }
    $.ajax({
        url: "ajax.php",
        type: 'POST',
        dataType: "json",
        data: {
            codigo: codigo,
            codigo_hijo: codigo_hijo,
            descripcion_compra: descripcion_compra,
            invima_compra: invima_compra,
            existencia_minima_compra: existencia_minima_compra,
            iva: iva,
            tipo_compra: tipo_compra,
            laboratorio_compra: laboratorio_compra,
            precio_compra_compra: precio_compra_compra,
            precio_venta_compra: precio_venta_compra,
            cant_menudeo: cant_menudeo,
            cant_blister: cant_blister,
            cant_global: cant_global,
            precio_menudeo: precio_menudeo,
            precio_blister: precio_blister,
            cantidad: cantidad,

            guardarPoductoBtn: 'guardarPoductoBtn'
        },
        success: function (response) {
            console.log(response);
            if (response == "producto guardado correctamente") {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: response,
                    showConfirmButton: false,
                    timer: 2000
                })
                location.reload();
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: response,
                    showConfirmButton: false,
                    timer: 2000
                })
                location.reload();
            }
        }
    }).fail(function (e) {
        console.log('Error!!' + JSON.stringify(e));
    })
}
function devolucion_venta(id, cantidad) {
    Swal.fire({
        title: '¿Estas seguro?',
        text: 'No podrás revertir esto',
        icon: 'info',
        showCancelButton: false,
        showDenyButton: true,
        denyButtonText: `Cancelar`,
        confirmButtonText: 'Confirmar',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "ajax.php",
                type: 'GET',
                dataType: "json",
                data: {
                    id: id,
                    cantidad: cantidad,
                    devolucion: 'devolucion'
                },
                success: function (response) {
                    console.log(response);
                    if (response == 'ok') {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Devolución exitosa',
                            showConfirmButton: false,
                            timer: 2000
                        })
                        location.reload();
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'A ocurrido un error',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        location.reload();
                    }
                }
            }).fail(function (e) {
                console.log('Error!!' + JSON.stringify(e));
            });
        }
    });
}
function calcularPrecioCierreCaja(e) {
    e.preventDefault();
    const efectivo = $("#total").val();
    const efectivoFisico = $('#efectivoFisico').val().replace(/[$,]/g, '');
    const total = efectivoFisico - efectivo;
    $('#resultSobrante').val(formatterPeso.format(total));
    return false;
}
function registrarCierreCaja() {
    let registrarCierreCaja = 'registrarCierreCaja';

    const devoluciones = $('#devol').val().replace(/[$,]/g, '');
    const efectivo = $('#efect').val().replace(/[$,]/g, '');
    const nequi = $('#nequi').val().replace(/[$,]/g, '');
    const daviplata = $('#daviplata').val().replace(/[$,]/g, '');
    const tarjeta = $('#tarjeta').val().replace(/[$,]/g, '');
    const efectivo_fisico = $('#efectivoFisico').val().replace(/[$,]/g, '');
    const cuanto_pagaste = $('#cuanto_pagaste').val().replace(/[$,]/g, '');
    const resultSobrante = $('#resultSobrante').val().replace(/[$,]/g, '');
    const observacion = $('#observacion').val();
    let id_cliente = $('#idcliente').val();
    let arrayData = [
        efectivo_fisico,
    ];
    // recorriendo y validando campos obligatorios
    for (let index = 0; index < arrayData.length; index++) {
        const element = arrayData[index];
        if (element == '' || element == undefined) {
            Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Debes llenar todos los campos obligatorios (*)',
                showConfirmButton: false,
                timer: 2000
            })
            $('#efectivoFisico').focus()
            return;
        }
    }
    $.ajax({
        url: "ajax.php",
        type: 'POST',
        dataType: "json",
        data: {
            devoluciones: devoluciones,
            efectivo: efectivo,
            nequi: nequi,
            daviplata: daviplata,
            tarjeta: tarjeta,
            efectivo_fisico: efectivo_fisico,
            cuanto_pagaste: cuanto_pagaste,
            resultSobrante: resultSobrante,
            observacion: observacion,
            registrarCierreCaja: registrarCierreCaja
        },
        success: function (response) {
            console.log(response);
            // let res = JSON.parse(response);
            if (response.id_compra != 'error') {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Cierre de caja exitoso',
                    showConfirmButton: false,
                    timer: 2000
                });
                let fech = new Date();
                generarPDFcierreCaja(response.id_compra, efectivo, nequi, daviplata, tarjeta, fech, efectivo_fisico, resultSobrante, observacion);

            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Hubo un error',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    }).fail(function (e) {

        console.log('Error!!' + JSON.stringify(e));

    });
}
function calcularPrecioCompra(e) {
    e.preventDefault();
    const cant_menudeo = $('#cant_menudeo').val();
    const cant_blister = $('#cant_blister').val();
    const cant_global = $('#cant_global').val();

    const precio_menudeo = $('#precio_menudeo').val().replace(/[$,]/g, '');
    const precio_compra = $('#precio_compra_compra').val().replace(/[$,]/g, '');
    const precio_venta = $('#precio_venta_compra').val().replace(/[$,]/g, '');

    const cantidad_compra = $('#cantidad_compra').val();

    var total_precio_compra = 0;

    if ($('#menudeo_menudeo').prop('checked')) {
        if (cant_menudeo == 0 && cant_blister == 0 && cant_global > 0) {
            console.log("entro solo unidad");
            total_precio_compra = precio_compra * cantidad_compra;

        } else if (cant_menudeo == 0 && cant_blister > 0) {
            console.log("entro solo blister");
            total_precio_compra = precio_compra / cant_global * cant_blister * cantidad_compra;

        } else if (cant_menudeo > 0 && cant_blister > 0 && cant_global > 0) {
            console.log("entro todo");
            total_precio_compra = precio_compra / cant_global * cantidad_compra;
        } else if (cant_menudeo > 0 && cant_blister == 0 && cant_global > 0) {
            console.log("entro menudeo y todo");
            total_precio_compra = precio_compra / cant_global * cantidad_compra;
        }
    } else {
        if (cant_menudeo == 0 && cant_blister == 0 && cant_global > 0) {
            console.log("entro solo unidad");
            total_precio_compra = precio_compra * cantidad_compra;

        } else if (cant_menudeo == 0 && cant_blister > 0) {
            console.log("entro solo blister");
            total_precio_compra = precio_compra * cantidad_compra;

        } else if (cant_menudeo > 0 && cant_blister > 0 && cant_global > 0) {
            console.log("entro todo");
            total_precio_compra = precio_compra * cantidad_compra;
        }
        else if (cant_menudeo > 0 && cant_blister == 0 && cant_global > 0) {
            console.log("entro menudeo y todo");
            total_precio_compra = precio_compra * cantidad_compra;
        }
    }
    console.log(total_precio_compra);
    $("#total_compra").val(formatterPeso.format(total_precio_compra));
    // $("#total_compra").val(formatterPeso.format(Math.round(total_precio_compra)));
}
function calcularPrecio(e) {
    e.preventDefault();
    const cant = $("#cantidad").val();
    var lote = $("#lote_venta").val();
    var cant_unidad;
    var precio;
    var tipo_venta;
    if ($('#menudeo_venta').prop('checked')) {
        precio = $('#precio_menudeo').val().replace(/[$,]/g, '');
        tipo_venta = 'menudeo';
        cant_unidad = $("#cant_menudeo_venta").val();
    } else if ($('#blister_venta').prop('checked')) {
        precio = $('#precio_blister').val().replace(/[$,]/g, '');
        tipo_venta = 'blister';
        cant_unidad = $("#cant_blister_venta").val();
    } else if ($('#global_venta').prop('checked')) {
        precio = $('#precio_venta').val().replace(/[$,]/g, '');
        cant_unidad = $("#cant_global_venta").val();
        tipo_venta = 'global';
    }

    const total = cant * precio;
    $('#sub_total').val(formatterPeso.format(total));
    if (e.which == 13) {
        if (lote == '') {
            $('#lote_venta').focus();
            return false;
        } else {
            if (cant > 0 && cant != '') {
                const id = $('#id').val();
                registrarDetalle(id, cant, precio, tipo_venta, cant_unidad, lote);
                $('#producto').focus();
            } else {
                $('#cantidad').focus();
                return false;
            }
        }
    }
}
function calcularPrecioBoton() {
    const cant = $("#cantidad").val();
    var lote = $("#lote_venta").val();
    var cant_unidad;
    var precio;
    var tipo_venta;
    if ($('#menudeo_venta').prop('checked')) {
        precio = $('#precio_menudeo').val().replace(/[$,]/g, '');
        tipo_venta = 'menudeo';
        cant_unidad = $("#cant_menudeo_venta").val();
    } else if ($('#blister_venta').prop('checked')) {
        precio = $('#precio_blister').val().replace(/[$,]/g, '');
        tipo_venta = 'blister';
        cant_unidad = $("#cant_blister_venta").val();
    } else if ($('#global_venta').prop('checked')) {
        precio = $('#precio_venta').val().replace(/[$,]/g, '');
        cant_unidad = $("#cant_global_venta").val();
        tipo_venta = 'global';
    }
    const total = cant * precio;
    $('#sub_total').val(formatterPeso.format(total));
    if (lote == '') {
        $('#lote_venta').focus();
        return false;
    } else {
        if (cant > 0 && cant != '') {
            const id = $('#id').val();
            registrarDetalle(id, cant, precio, tipo_venta, cant_unidad, lote);
            $('#producto').focus();
        } else {
            $('#cantidad').focus();
            return false;
        }
    }
}
function agregarCompraLista(e) {
    e.preventDefault();
    // variables
    const cant_menudeo = $('#cant_menudeo').val();
    const cant_blister = $('#cant_blister').val();
    const precio_menudeo = $('#precio_menudeo').val().replace(/[$,]/g, '');
    const precio_blister = $('#precio_blister').val().replace(/[$,]/g, '');
    const precio_compra = $('#precio_compra_compra').val().replace(/[$,]/g, '');
    const precio_venta = $('#precio_venta_compra').val().replace(/[$,]/g, '');
    const cant_global = $('#cant_global').val();
    const cantidad_compra = $('#cantidad_compra').val();
    const vencimiento_compra = $('#vencimiento_compra').val();
    const laboratorio_compra = $('#laboratorio_compra').val();
    const lote_compra = $('#lote_compra').val();
    var total_precio_compra = 0;
    var total_precio_venta = 0;
    let cantunitario = 0;
    const id = $('#id_compra').val();
    var cantSave = 0;
    // validando si es menudeo o no
    if ($('#menudeo_menudeo').prop('checked')) {
        // si es menudeo
        cantSave = parseFloat(cantidad_compra) / parseFloat(cantidad_compra);
        if (cant_menudeo == 0 && cant_blister == 0 && cant_global > 0) {
            console.log("entro solo unidad");
            total_precio_venta = precio_venta;
            total_precio_compra = precio_compra;
            cantunitario = cantidad_compra * cant_global;

        } else if (cant_menudeo == 0 && cant_blister > 0) {
            console.log("entro solo blister");
            total_precio_venta = precio_venta / cant_global * cant_blister;
            total_precio_compra = precio_compra / cant_global * cant_blister;
            cantunitario = cantidad_compra * cant_blister;
        } else if (cant_menudeo > 0 && cant_blister > 0 && cant_global > 0) {
            console.log("entro solo todo");
            total_precio_venta = precio_menudeo;
            total_precio_compra = precio_compra / cant_global;
            cantunitario = cantidad_compra * cant_menudeo;
        } else if (cant_menudeo > 0 && cant_blister == 0 && cant_global > 0) {
            console.log("entro menudeo y todo");
            total_precio_venta = precio_menudeo;
            total_precio_compra = precio_compra / cant_global;
            cantunitario = cantidad_compra * cant_menudeo;
        }
    } else {
        cantunitario = cantidad_compra * cant_global;
        if (cant_menudeo == 0 && cant_blister == 0 && cant_global > 0) {
            console.log("entro solo unidad");
            total_precio_venta = precio_venta;
            total_precio_compra = precio_compra;

        } else if (cant_menudeo == 0 && cant_blister > 0) {
            console.log("entro solo blister");
            total_precio_venta = precio_venta;
            total_precio_compra = precio_compra;

        } else if (cant_menudeo > 0 && cant_blister > 0 && cant_global > 0) {
            console.log("entro solo todo");
            total_precio_venta = precio_venta;
            total_precio_compra = precio_compra;
        } else if (cant_menudeo > 0 && cant_blister == 0 && cant_global > 0) {
            console.log("entro menudeo y todo");
            total_precio_venta = precio_venta;
            total_precio_compra = precio_compra;
        }
    }

    console.log(cantunitario);

    // validar campos vacios
    if (lote_compra == '' || precio_compra == '' || precio_compra == 0 || precio_venta == '' || precio_venta == 0 || cantidad_compra == '' || cantidad_compra == 0 || vencimiento_compra == '' || vencimiento_compra == 0) {
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Todos los campos deben estar llenos',
            showConfirmButton: false,
            timer: 2000
        });
    } else {
        // enviar data para guardar a funcion
        registrarDetalleCompra(e, id, cantunitario, cantidad_compra, total_precio_compra, total_precio_venta, vencimiento_compra, laboratorio_compra, lote_compra, precio_menudeo, precio_blister);
    }
}
function calcularDescuento(e, id) {
    if (e.which == 13) {
        let descuento = 'descuento';
        console.log(e.target.value);
        $.ajax({
            url: "ajax.php",
            type: 'GET',
            dataType: "json",
            data: {
                id: id,
                desc: e.target.value,
                descuento: descuento
            },
            success: function (response) {

                if (response.mensaje == 'descontado') {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Descuento Aplicado',
                        showConfirmButton: false,
                        timer: 2000
                    })
                    listar();
                } else { }
            }
        });
    }
}
function calcularDescuentoBtn(id) {
    let descuento = 'descuento';
    const desc = $('#desc').val();
    $.ajax({
        url: "ajax.php",
        type: 'GET',
        dataType: "json",
        data: {
            id: id,
            desc: parseInt(desc),
            descuento: descuento
        },
        success: function (response) {
            if (response.mensaje == 'descontado') {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Descuento Aplicado',
                    showConfirmButton: false,
                    timer: 2000
                })
                listar();
            } else { }
        }
    });
}
function listar() {
    let html = '';
    let detalle = 'detalle';
    $.ajax({
        url: "ajax.php",
        dataType: "json",
        data: {
            detalle: detalle
        },
        success: function (response) {
            let count = 0;
            response.forEach(row => {
                count = count + 1;
                html += `<tr>
                <td>${row['codigo']}</td>
                <input class="form-control codigo_v"  type="hidden" value="${row['codigo']}" id="codigo_v">
                <td>${row['lote']}</td>
                <input class="form-control lote_v" type="hidden" value="${row['lote']}" id="lote_v">
                <td>${row['descripcion']}</td>
                <td width="100">
                <input class="form-control" placeholder="Desc" type="number" onkeyup="calcularDescuento(event, ${row['id']})" id="desc">
                <button class="btn btn-info p-2 update" type="button" onclick="calcularDescuentoBtn(${row['id']})">
                <i class="fas fa-redo-alt"></i>
                </button>
                </td>
                <td>${formatterPeso.format(row['descuento'])}</td>
                <td>
                <input class="form-control nueva_cantidad_venta${count}" placeholder="Cantidad" type="number" value="${row['cantidad']}" id="nueva_cantidad_venta">
                <input class="form-control nueva_cant_unidad_venta${count}" type="hidden" value="${row['cant_unidad']}" id="nueva_cant_unidad_venta">
                <button class="btn btn-info p-2 update" type="button" onclick="actualizarCantidadVenta(${row['id']}, ${count})">
                <i class="fas fa-redo-alt"></i>
                </button>
                </td>
                <td>
                ${formatterPeso.format(row['precio_venta'])}
                <input class="form-control nuevo_precio_ven${count}" placeholder="Cantidad" type="hidden" value="${row['precio_venta']}" id="nuevo_precio_ven">
                </td>
                <td class='d-none'>
                ${row['sub_total']}
                </td>
                <td>
                ${formatterPeso.format(row['sub_total'])}
                </td>
                <td><button class="btn btn-danger" type="button" onclick="deleteDetalle(${row['id']})">
                <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            });
            document.querySelector("#detalle_venta").innerHTML = html;
            calcular();
        }
    });
}
function actualizarCantidadVenta(id, count) {
    const nueva_cantidad_venta = parseInt($(".nueva_cantidad_venta" + count + "").val());
    const nueva_cant_unidad_venta = parseInt($(".nueva_cant_unidad_venta" + count + "").val());
    const nuevo_precio_ven = parseInt($(".nuevo_precio_ven" + count + "").val());
    const codigo = $(".codigo_v").val();
    const lote = $(".lote_v").val();
    // 
    if (nueva_cantidad_venta == '' || nueva_cantidad_venta == undefined || nueva_cantidad_venta == 0) {
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Debes llenar todos los campos obligatorios (*)',
            showConfirmButton: false,
            timer: 2000
        })
        return;
    }

    ;
    // const multiplicar = 0;
    const multiplicarTotal = nuevo_precio_ven * nueva_cantidad_venta;
    $.ajax({
        url: "ajax.php",
        type: 'POST',
        dataType: "json",
        data: {
            id: codigo,
            lote: lote,
            nueva_cantidad_venta: nueva_cantidad_venta,
            nueva_cant_unidad_venta: nueva_cant_unidad_venta,
            id_detalle_venta: id,
            sub_total: multiplicarTotal,
            actualizar_cantidad_venta: 'actualizar_cantidad_venta',
        },
        success: function (response) {
            if (response.mensaje == 'exitoso') {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Cantidad actualizada correctamente',
                    showConfirmButton: false,
                    timer: 2000
                });
                listar();
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: response.mensaje,
                    showConfirmButton: false,
                    timer: 2000
                });
                listar();
            }
        }
    }).fail(function (e) {
        console.log('Error!!' + JSON.stringify(e));
        // location.reload();
    });
}
function actualizarCantidad(id, count) {
    const nueva_cantidad = parseInt($(".nueva_cantidad" + count + "").val());
    // const nuevo_total = parseInt($(".nuevo_total1").val().replace(/[$,]/g, ''));
    const nuevo_total = parseInt($(".nuevo_total" + count + "").val());
    const nuevo_precio_compra = parseInt($(".nuevo_precio_compra" + count + "").val());
    console.log(nuevo_total);
    // array campos obligatorios
    let arrayData = [
        nueva_cantidad, nuevo_total
    ];
    const multiplicar = nuevo_precio_compra * nueva_cantidad;
    // recorriendo y validando campos obligatorios
    for (let index = 0; index < arrayData.length; index++) {
        const element = arrayData[index];
        if (element == '' || element == undefined) {
            Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Debes llenar todos los campos obligatorios (*)',
                showConfirmButton: false,
                timer: 2000
            })
            return;
        }
        $.ajax({
            url: "ajax.php",
            type: 'POST',
            dataType: "json",
            data: {
                nueva_cantidad: nueva_cantidad,
                nuevo_total: multiplicar,
                id_detalle_compra: id,
                actualizar_cantidad_compra: 'actualizar_cantidad_compra',
            },
            success: function (response) {
                console.log(response);
                if (response.mensaje == 'exitoso') {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Cantidad actualizada',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    location.reload();
                } else {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Hubo un error intenta de nuevo',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    location.reload();
                }
            }
        }).fail(function (e) {
            console.log('Error!!' + JSON.stringify(e));
        });
    }

}
function actualizarTotal(id, count) {
    // const nuevo_total = parseInt($(".nuevo_total" + count + "").val().replace(/[$,]/g, ''));
    const nuevo_total = parseInt($(".nuevo_total" + count + "").val());
    // array campos obligatorios
    let arrayData = [
        nuevo_total
    ];
    // recorriendo y validando campos obligatorios
    for (let index = 0; index < arrayData.length; index++) {
        const element = arrayData[index];
        if (element == '' || element == undefined) {
            Swal.fire({
                position: 'center',
                icon: 'info',
                title: 'Debes llenar todos los campos obligatorios (*)',
                showConfirmButton: false,
                timer: 2000
            })
            return;
        }
        $.ajax({
            url: "ajax.php",
            type: 'POST',
            dataType: "json",
            data: {
                nuevo_total: nuevo_total,
                id_detalle_compra: id,
                actualizar_total_compra: 'actualizar_total_compra',
            },
            success: function (response) {
                console.log(response);
                if (response.mensaje == 'exitoso') {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Total actualizado',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    location.reload();
                } else {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Hubo un error intenta de nuevo',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    location.reload();
                }
            }
        }).fail(function (e) {
            console.log('Error!!' + JSON.stringify(e));
        });
    }

}
function actualizarCantidadCompra(id) {
    const nueva_cantidad = parseInt($(".nueva_cantidad").val());
    const id_detalle_compra = id;
    var parametros = [];
    $("table tbody tr").each(function (i, e) {
        var tr = [];
        $(this).find("td").each(function (index, element) {
            if (index != 0) // ignoramos el primer indice que dice Option #
            {
                var td = {};
                td = $(this).text();
                tr.push(td);
            }
        });
        parametros.push(tr);
    });
    // console.log(parametros);
    let Cant = parametros[0][7];
    let total = parametros[0][9];

    // $.ajax({
    //     url: "ajax.php",
    //     type: 'POST',
    //     dataType: "json",
    //     data: {
    //         Cant: Cant,
    //         total: total,
    //         id_detalle_compra: id_detalle_compra,
    //         actualizar_cantidad_compra: 'actualizar_cantidad_compra',
    //     },
    //     success: function (response) {
    //         console.log(response);
    //         if (response.mensaje == 'exitoso') {
    //             Swal.fire({
    //                 position: 'center',
    //                 icon: 'success',
    //                 title: 'Cantidad actualizado exitosamente',
    //                 showConfirmButton: false,
    //                 timer: 2000
    //             });
    //             location.reload();
    //         } else {
    //             Swal.fire({
    //                 position: 'center',
    //                 icon: 'error',
    //                 title: 'Hubo un error intenta de nuevo',
    //                 showConfirmButton: false,
    //                 timer: 2000
    //             });
    //             location.reload();
    //         }
    //     }
    // }).fail(function (e) {

    //     console.log('Error!!' + JSON.stringify(e));

    // });

}
function actualizarValorTotalCompra(id) {
    const nuevo_valor = parseInt($(".nuevo_valor").val().replace(/[$,]/g, ''));
    const id_detalle_compra = id;
    console.log(nuevo_valor);
    console.log(id_detalle_compra);

    $.ajax({
        url: "ajax.php",
        type: 'POST',
        dataType: "json",
        data: {
            nuevo_valor: nuevo_valor,
            id_detalle_compra: id_detalle_compra,
            actualizar_total_compra: 'actualizar_total_compra',
        },
        success: function (response) {
            console.log(response);
            if (response.mensaje == 'exitoso') {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Total actualizado exitosamente',
                    showConfirmButton: false,
                    timer: 2000
                });
                // location.reload();
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Hubo un error intenta de nuevo',
                    showConfirmButton: false,
                    timer: 2000
                });
                // location.reload();
            }
        }
    }).fail(function (e) {

        console.log('Error!!' + JSON.stringify(e));

    });
}
function mostrarProducto(cod_barras) {
    let action = 'mostrarProd';
    $.ajax({
        url: "ajax.php",
        type: 'POST',
        dataType: "json",
        data: {
            cod_barras: cod_barras,
            regDetalle: action,
        },
        success: function (response) {
            if (response == 'registrado') {
                $('#cantidad').val('');
                $('#precio').val('');
                $("#producto").val('');
                $("#sub_total").val('');
                $("#producto").focus();
                listar();
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Producto Ingresado',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });
}
function registrarDetalle(id, cant, precio, tipo_venta, cant_unidad, lote) {
    if (document.getElementById('producto').value != '') {
        if (id != null) {
            let action = 'regDetalle';
            $.ajax({
                url: "ajax.php",
                type: 'POST',
                dataType: "json",
                data: {
                    id: id,
                    cant: cant,
                    regDetalle: action,
                    precio: precio,
                    cant_unidad: parseInt(cant_unidad),
                    tipo_venta: tipo_venta,
                    lote: lote
                },
                success: function (response) {
                    console.log(response);
                    if (response == 'No hay unidades') {
                        $("#cantidad").focus();
                        listar();
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'El producto no tiene unidades suficientes',
                            showConfirmButton: false,
                            timer: 2000
                        })
                    }
                    else if (response == 'registrado') {
                        $('#form_ventas')[0].reset();
                        $('#lote_venta').find('option').remove();
                        $("#producto").focus();
                        listar();
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Producto Ingresado',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else if (response == 'actualizado') {
                        $('#form_ventas')[0].reset();
                        $('#lote_venta').find('option').remove();
                        $("#producto").focus();
                        listar();
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Producto Actualizado',
                            showConfirmButton: false,
                            timer: 2000
                        })
                    } else if (response == 'No hay las unidades suficientes en ese lote') {
                        $("#lote_venta").focus();
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: response,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        listar();
                    } else {
                        $('#form_ventas')[0].reset();
                        $("#producto").focus();
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: response,
                            showConfirmButton: false,
                            timer: 2000
                        })
                    }
                }
            }).fail(function (e) {

                console.log('Error!!' + JSON.stringify(e));

            });
        }
    }
}
function registrarDetalleCompra(e, id, cantunitario, cantSeleccionado, precio_compra, precio_venta, vencimiento_compra, laboratorio_compra, lote_compra, precio_menudeo, precio_blister) {
    let action = 'regDetalleCompra';
    $.ajax({
        url: "ajax.php",
        type: 'POST',
        // dataType: "json",
        data: {
            id: id,
            cant: cantunitario,
            cantSeleccionado: cantSeleccionado,
            regDetalleCompra: action,
            precio_compra: precio_compra,
            precio_venta: precio_venta,
            vencimiento_compra: vencimiento_compra,
            laboratorio_compra: laboratorio_compra,
            lote_compra: lote_compra,
            precio_menudeo: precio_menudeo,
            precio_blister: precio_blister,
        },
        success: function (response) {
            console.log(response);
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: response,
                showConfirmButton: false,
                timer: 2000
            })
            $('#form_compras')[0].reset();
            $("#menudeo_compra").prop("checked", false);
            $('#producto_compra').focus();
            location.reload();
        }, error(e) {
            console.log(JSON.stringify(e));
        }
    });
}
function veirificarExistencia(cant) {
    // alert(cant)
    $.ajax({
        url: "ajax.php",
        dataType: "json",
        data: {
            cant: cant
        },
        success: function (data) {
            // response(data);
        }
    });
}
function deleteDetalle(id) {
    let detalle = 'Eliminar'
    $.ajax({
        url: "ajax.php",
        data: {
            id: id,
            delete_detalle: detalle
        },
        success: function (response) {
            if (response == 'restado') {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Producto Descontado',
                    showConfirmButton: false,
                    timer: 2000
                });
                $("#producto").val('');
                $("#precio").val('');
                $("#cantidad").val('');
                $("#presentacion").val('');
                $("#presentacion_label").val('');
                $("#cant_presentacion").val('');
                $("#existencia_venta").val('');
                $("#existencia_venta").val('');
                $("#sub_total").val('');
                $("#precioFormat").val(formatterPeso.format(''));
                document.querySelector("#producto").value = '';
                document.querySelector("#producto").focus();
                listar();
            } else if (response == 'ok') {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Producto Eliminado',
                    showConfirmButton: false,
                    timer: 2000
                });
                $("#producto").val('');
                $("#precio").val('');
                $("#cantidad").val('');
                $("#presentacion").val('');
                $("#presentacion_label").val('');
                $("#cant_presentacion").val('');
                $("#existencia_venta").val('');
                $("#sub_total").val('');
                $("#precioFormat").val(formatterPeso.format(''));
                document.querySelector("#producto").value = '';
                document.querySelector("#producto").focus();
                listar();
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Error al eliminar el producto',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });
}
function deleteDetalleCompra(id) {
    let detalle = 'Eliminar'
    $.ajax({
        url: "ajax.php",
        data: {
            id: id,
            delete_detalle_compra: detalle
        },
        success: function (response) {

            if (response == 'restado') {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Producto Descontado',
                    showConfirmButton: false,
                    timer: 2000
                })
                document.querySelector("#producto_compra").value = '';
                document.querySelector("#producto_compra").focus();
                location.reload();
            } else if (response == 'ok') {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Producto Eliminado',
                    showConfirmButton: false,
                    timer: 2000
                })
                document.querySelector("#producto_compra").value = '';
                document.querySelector("#producto_compra").focus();
                location.reload();
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Error al eliminar el producto',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });
}
function calcular() {
    // obtenemos todas las filas del tbody
    var filas = document.querySelectorAll("#tblDetalle tbody tr");

    var total = 0;

    // recorremos cada una de las filas
    filas.forEach(function (e) {

        // obtenemos las columnas de cada fila
        var columnas = e.querySelectorAll("td");

        // obtenemos los valores de la cantidad y importe
        var importe = parseFloat(columnas[7].textContent);

        total += importe;
    });

    // mostramos la suma total
    var filas = document.querySelectorAll("#tblDetalle tfoot tr td");
    filas[7].textContent = formatterPeso.format(total.toFixed(2));
}
function calcularCompra() {
    // obtenemos todas las filas del tbody
    // var filas = document.querySelectorAll("#tblDetalleCompra tbody tr td");
    // var total = 0;
    // // recorremos cada una de las filas
    // console.log(filas);
    // filas.forEach(function (e) {
    //     console.log(e);
    //     // obtenemos las columnas de cada fila
    //     var columnas = e.querySelectorAll("td");
    //     // obtenemos los valores de la cantidad y importe
    //     var importe = parseFloat(columnas[9].textContent);
    //     console.log(importe);
    //     total += importe;
    // });
    // // mostramos la suma total
    // var filas = document.querySelectorAll("#tblDetalleCompra tfoot tr td");
    // filas[9].textContent = formatterPeso.format(total.toFixed(2));
}
function generarPDF(cliente, id_venta) {
    url = 'pdf/generar.php?cl=' + cliente + '&v=' + id_venta;
    window.open(url, '_blank');
}
function generarPDFcompra(id_compra) {
    url = 'pdf/generar_compra.php?cl=' + '&v=' + id_compra;
    window.open(url, '_blank');
}
function generarPDFcierreCaja(id_venta, efectivo, nequi, daviplata, tarjeta, fecha, efectivo_fisico, resultSobrante, observacion) {
    url = 'pdf/generar_cierre_caja_close.php?v=' + id_venta + '&efec=' + efectivo + '&nequi=' + nequi + '&daviplata=' + daviplata + '&tarjeta=' + tarjeta + '&fecha=' + fecha + '&efec_fis=' + efectivo_fisico + '&sobr=' + resultSobrante + '&obs=' + observacion + '&creando=si';
    window.open(url, '_blank');
    // location.href = 'salir.php';
    location.reload();
    // window.open(url, '_blank');
}
if (document.getElementById("stockMinimo")) {
    const action = "sales";
    $.ajax({
        url: 'chart.php',
        type: 'POST',
        data: {
            action
        },
        async: true,
        success: function (response) {
            if (response != 0) {
                var data = JSON.parse(response);
                var nombre = [];
                var cantidad = [];
                for (var i = 0; i < data.length; i++) {
                    let nombreCorto = data[i]['descripcion'].slice(0, 13);
                    nombre.push(nombreCorto);
                    cantidad.push(data[i]['existencia']);
                }
                var ctx = document.getElementById("stockMinimo");
                var myPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: nombre,
                        datasets: [{
                            data: cantidad,
                            backgroundColor: ['#024A86', '#E7D40A', '#581845', '#C82A54', '#EF280F', '#8C4966', '#FF689D', '#E36B2C', '#69C36D', '#23BAC4'],
                        }],
                    },
                });
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
}
if (document.getElementById("ProductosVendidos")) {
    const action = "polarChart";
    $.ajax({
        url: 'chart.php',
        type: 'POST',
        async: true,
        data: {
            action
        },
        success: function (response) {
            if (response != 0) {
                var data = JSON.parse(response);
                var nombre = [];
                var cantidad = [];
                for (var i = 0; i < data.length; i++) {
                    nombre.push(data[i]['descripcion']);
                    cantidad.push(data[i]['cantidad']);
                }
                var ctx = document.getElementById("ProductosVendidos");
                var myPieChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: nombre,
                        datasets: [{
                            data: cantidad,
                            backgroundColor: ['#C82A54', '#EF280F', '#23BAC4', '#8C4966', '#FF689D', '#E7D40A', '#E36B2C', '#69C36D', '#581845', '#024A86'],
                        }],
                    },
                });
            }
        },
        error: function (error) {
            console.log(error);

        }
    });
}
function guardarTemperaturaHumedad(hora) {
    let temperatura = '';
    let humedad = '';
    let fecha_tomada = $("#fecha_tomada").val();
    if (hora == '9') {
        temperatura = $("#temp_9am").val();
        humedad = $("#humedad_9am").val();
    } else if (hora == '1') {
        temperatura = $("#temp_1pm").val();
        humedad = $("#humedad_1pm").val();
    } else if (hora == '5') {
        temperatura = $("#temp_5pm").val();
        humedad = $("#humedad_5pm").val();
    }
    $.ajax({
        url: "ajax.php",
        type: 'POST',
        data: {
            temperatura: temperatura,
            humedad: humedad,
            hora: hora,
            fecha_tomada: fecha_tomada,
            temperatura_humedad: 'temperatura_humedad'
        },
        success: function (response) {
            console.log(response);
            if (response == 'ya existe') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'La temperatura de esta hora y fecha ya fue almacenada anteriormente',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else if (response == 'ok') {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Temperatura y humedad de las ' + hora + ' tomadas con exito',
                    showConfirmButton: false,
                    // timer: 2000
                });
                location.reload();
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Ha ocurrido un error inesperado, vuelve a intentarlo',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    }).fail(function (e) {
        console.log('Error!!' + JSON.stringify(e));
    });

}
function guardarLimpezaDesinfeccion() {
    let fecha_limpieza = $("#fecha_limpieza").val();
    let area_aseo = $("#area_aseo").val();
    let solucion_sanizante = $("#solucion_sanizante").val();
    let usuario_limpieza = $("#usuario_limpieza").val();

    $.ajax({
        url: "ajax.php",
        type: 'POST',
        data: {
            area_aseo: area_aseo,
            solucion_sanizante: solucion_sanizante,
            usuario_limpieza: usuario_limpieza,
            fecha_limpieza: fecha_limpieza,
            limpieza_desinfeccion: 'limpieza_desinfeccion'
        },
        success: function (response) {
            console.log(response);
            if (response == 'ok') {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Limpieza y desinfección alamcenados con exito',
                    showConfirmButton: false,
                    timer: 2000
                })
                location.reload();
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Ha ocurrido un error inesperado, vuelve a intentarlo',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    }).fail(function (e) {
        console.log('Error!!' + JSON.stringify(e));
    });
}
function guardarResiduos() {
    let fecha_residuos = $("#fecha_residuos").val();
    let Biodegradables = $("#Biodegradables").val();
    let Reciclables = $("#Reciclables").val();
    let Inertes = $("#Inertes").val();
    let Ordinarios = $("#Ordinarios").val();
    let Biosanitarios = $("#Biosanitarios").val();
    let Anatomopatologicos = $("#Anatomopatologicos").val();
    let Cortopunzantes = $("#Cortopunzantes").val();
    let DeAnimales = $("#DeAnimales").val();
    let Fuentes_abiertas = $("#Fuentes_abiertas").val();
    let Fuentes_cerradas = $("#Fuentes_cerradas").val();
    let Farmacos = $("#Farmacos").val();
    let Citotoxicos = $("#Citotoxicos").val();
    let Metales_pesados = $("#Metales_pesados").val();
    let Reactivos = $("#Reactivos").val();
    let Contenedores_presurizados = $("#Contenedores_presurizados").val();
    let Aceites_usados = $("#Aceites_usados").val();

    $.ajax({
        url: "ajax.php",
        type: 'POST',
        data: {
            fecha_residuos: fecha_residuos,
            Biodegradables: Biodegradables,
            Reciclables: Reciclables,
            Inertes: Inertes,
            Ordinarios: Ordinarios,
            Biosanitarios: Biosanitarios,
            Anatomopatologicos: Anatomopatologicos,
            Cortopunzantes: Cortopunzantes,
            DeAnimales: DeAnimales,
            Fuentes_abiertas: Fuentes_abiertas,
            Fuentes_cerradas: Fuentes_cerradas,
            Farmacos: Farmacos,
            Citotoxicos: Citotoxicos,
            Metales_pesados: Metales_pesados,
            Reactivos: Reactivos,
            Contenedores_presurizados: Contenedores_presurizados,
            Aceites_usados: Aceites_usados,
            residuos: 'residuos'
        },
        success: function (response) {
            console.log(response);
            if (response == 'ok') {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'residuos almacenados con exito',
                    showConfirmButton: false,
                    timer: 2000
                })
                location.reload();
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Ha ocurrido un error inesperado, vuelve a intentarlo',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    }).fail(function (e) {
        console.log('Error!!' + JSON.stringify(e));
    });
}
function btnCambiar(e) {
    e.preventDefault();
    const actual = document.getElementById('actual').value;
    const nueva = document.getElementById('nueva').value;
    if (actual == "" || nueva == "") {
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Los campos estan vacios',
            showConfirmButton: false,
            timer: 2000
        })
    } else {
        const cambio = 'pass';
        $.ajax({
            url: "ajax.php",
            type: 'POST',
            data: {
                actual: actual,
                nueva: nueva,
                cambio: cambio
            },
            success: function (response) {
                if (response == 'ok') {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Contraseña modificado',
                        showConfirmButton: false,
                        timer: 2000
                    })
                    document.querySelector('#frmPass').reset();
                    $("#nuevo_pass").modal("hide");
                } else if (response == 'dif') {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'La contraseña actual incorrecta',
                        showConfirmButton: false,
                        timer: 2000
                    })
                } else {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Error al modificar la contraseña',
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
            }
        });
    }
}
function editarCliente(id) {
    const action = "editarCliente";
    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        async: true,
        data: {
            editarCliente: action,
            id: id
        },
        success: function (response) {
            const datos = JSON.parse(response);
            $('#cedulaNit').val(datos.cedulaNit);
            $('#nombre').val(datos.nombre);
            $('#telefono').val(datos.telefono);
            $('#direccion').val(datos.direccion);
            $('#id').val(datos.idcliente);
            $('#btnAccion').val('Modificar');
        },
        error: function (error) {
            console.log(error);

        }
    });
}
function editarUsuario(id) {
    const action = "editarUsuario";
    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        async: true,
        data: {
            editarUsuario: action,
            id: id
        },
        success: function (response) {
            const datos = JSON.parse(response);
            $('#nombre').val(datos.nombre);
            $('#usuario').val(datos.usuario);
            $('#correo').val(datos.correo);
            $('#id').val(datos.idusuario);
            $('#btnAccion').val('Modificar');
        },
        error: function (error) {
            console.log(error);

        }
    });
}
function editarProducto(id) {
    const action = "editarProducto";
    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        async: true,
        data: {
            editarProducto: action,
            id: id
        },
        success: function (response) {
            const datos = JSON.parse(response);
            let existencia = 0;
            // 
            if (datos.precio_menudeo > 0) {
                $('#precio_menudeo').prop('disabled', false);
                $('#cant_menudeo').prop('disabled', false);
                $("#menudeo").prop("checked", true);
            } else {
                $('#precio_menudeo').prop('disabled', true);
                $('#cant_menudeo').prop('disabled', true);
                $("#menudeo").prop("checked", false);
            }

            if (datos.precio_blister > 0) {
                $('#precio_blister').prop('disabled', false);
                $('#cant_blister').prop('disabled', false);
                $("#blister").prop("checked", true);
            } else {
                $('#precio_blister').prop('disabled', true);
                $('#cant_blister').prop('disabled', true);
                $("#blister").prop("checked", false);
            }

            $('#codigo1').val(datos.codigo);
            $('#codigo_hijo').val(datos.codigo_hijo);
            $('#producto').val(datos.descripcion);
            $('#cant_menudeo').val(datos.cant_menudeo);
            $('#cant_blister').val(datos.cant_blister);
            $('#cant_global').val(datos.cant_global);
            $('#precio_compra').val(formatterPeso.format(datos.precio_compra));
            $('#invima').val(datos.invima);
            $('#existencia_minima').val(datos.existencia_minima);
            $('#precio').val(formatterPeso.format(datos.precio_global));
            $('#precio_menudeo').val(formatterPeso.format(datos.precio_menudeo));
            $('#precio_blister').val(formatterPeso.format(datos.precio_blister));
            $('#id').val(datos.codproducto);
            $('#tipo').val(datos.id_tipo);
            $('#laboratorio').val(datos.id_lab);
            $('#iva').val(datos.iva);
            if (datos[17].length > 0) {
                existencia = datos[17];
            }
            $('#cantidad').val(existencia);
            $('#existencia_minima').val(datos.existencia_minima);
            $("#accion").prop("checked", true);
            $('#btnAccion').val('Modificar');
        },
        error: function (error) {
            console.log(error);
        }
    });
}
function limpiar() {
    $('#formulario')[0].reset();
    $('#id').val('');
    $('.presentacion2').val('');
    $('#btnAccion').val('Registrar');
}
function editarTipo(id) {
    const action = "editarTipo";
    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        async: true,
        data: {
            editarTipo: action,
            id: id
        },
        success: function (response) {
            const datos = JSON.parse(response);
            $('#nombre').val(datos.tipo);
            $('#id').val(datos.id);
            $('#btnAccion').val('Modificar');
        },
        error: function (error) {
            console.log(error);

        }
    });
}
function editarPresent(id) {
    const action = "editarPresent";
    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        async: true,
        data: {
            editarPresent: action,
            id: id
        },
        success: function (response) {
            const datos = JSON.parse(response);
            $('#nombre').val(datos.nombre);
            $('#nombre_corto').val(datos.nombre_corto);
            $('#id').val(datos.id);
            $('#btnAccion').val('Modificar');
        },
        error: function (error) {
            console.log(error);

        }
    });
}
function editarLab(id) {
    const action = "editarLab";
    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        async: true,
        data: {
            editarLab: action,
            id: id
        },
        success: function (response) {
            const datos = JSON.parse(response);
            $('#laboratorio').val(datos.laboratorio);
            $('#direccion').val(datos.direccion);
            $('#id').val(datos.id);
            $('#btnAccion').val('Modificar');
        },
        error: function (error) {
            console.log(error);

        }
    });
}
function editarProveedor(id) {
    const action = "editarProvedor";
    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        async: true,
        data: {
            editarProvedor: action,
            id: id
        },
        success: function (response) {
            const datos = JSON.parse(response);
            $('#id').val(datos.id);
            $('#proveedor').val(datos.proveedor);
            $('#nit').val(datos.nit);
            $('#telefono').val(datos.telefono);
            $('#btnAccion').val('Modificar');
        },
        error: function (error) {
            console.log(error);

        }
    });
}

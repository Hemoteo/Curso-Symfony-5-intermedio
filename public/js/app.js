$('.finalizar').on('click', function (e) {
    e.preventDefault();
    var $this = $(this),
        url = $this.data('url'),
        $descripcion = $this.closest('tr').find('.descripcion')
    let textoContrario = $this.data('textoContrario');
    let textoActual = $this.text();

    $this.addClass('disabled');

    $.post(url, {})
        .done(function (respuesta) {
            if (respuesta.finalizada) {
                $descripcion.html('<s>'+$descripcion.text()+'</s>');
            } else {
                $descripcion.html($descripcion.text());
            }
            $this.text(textoContrario);
            $this.data('textoContrario', textoActual);
            $this.removeClass('disabled');
        })
        .fail(function () {
            $this.removeClass('disabled');
        });
});
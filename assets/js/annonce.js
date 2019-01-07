$("#annonce_image").click(function(){
    //Je récupère le numéro des futurs champs que je vais créer
    const index = +$("#widgets-counter").val();

    //Je récupère le prototype des entrées
    const tmpl = $('#annonce_images').data('prototype').replace(/__name__/g, index);

    //J'injecte ce code au sein de la div
    $("#annonce_images").append(tmpl);

    $("#widgets-counter").val(index + 1);

    //Je gère le bouton supprimer
    handleDeleteButtons();

});

function updateCounter(){
    const target = +$('#annonce_images div.form-group').length;

    $('#widgets-counter').val(target);
}

function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function(){
        const target = $(this).data('target');
        $(target).remove();
    })
}

updateCounter();
handleDeleteButtons();
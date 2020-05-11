/**
 * Created by deyvison.pereira on 27/10/2017.
 */
if(isFavoritos(window.location.href)){
    $('.favoritar').css('color', '#d1ae00');
}

$(document).ready(function(){
    $('.div-favoritar').click(function(){
        var listaFavoritos = localStorage.getItem('ecommerce-favoritos');

        if(listaFavoritos == null)
            listaFavoritos = [];
        else
            listaFavoritos = JSON.parse(listaFavoritos);

        if(!isFavoritos(window.location.href)) {
            listaFavoritos = addFavorito(window.location.href, listaFavoritos);
            $('.favoritar').css('color', '#d1ae00');
        }else {
            listaFavoritos = removerFavorito(window.location.href, listaFavoritos);
            $('.favoritar').css('color', '#bcbcbc');
        }

        localStorage.setItem('ecommerce-favoritos', JSON.stringify(listaFavoritos));
    });

    if(document.title.replace('E-commerce QiSat: ', '') == 'Pages'){
        var listaFavoritos = localStorage.getItem('ecommerce-favoritos');

        if(listaFavoritos != null){
            listaFavoritos = JSON.parse(listaFavoritos);

            var htmlList = '<ul class="list-group">';
            listaFavoritos.forEach(function(favorito){//<i class="glyphicon glyphicon-remove"></i>
                htmlList += '<li class="list-group-item col-md-4"><a href="'+favorito.link+'">';
                htmlList += favorito.nome+'</a>';
                htmlList += '<i class="glyphicon glyphicon-remove remover-favorito" onclick="removerFavoritoClick(\''+favorito.link+'\')" style="float: right;cursor: pointer;">';
                htmlList += '</i></li>';
            });
            htmlList += '</ul>';

            $('#menuLista').html(htmlList);
        }
    }
});

function addFavorito(link, listaFavoritos){
    var pagina = {
        'nome': document.title.replace('E-commerce QiSat:', '').trim(),
        'link': link
    }
    listaFavoritos.push(pagina);
    return listaFavoritos;
}

function removerFavorito(link, listaFavoritos){
    var listaAux = [];
    listaFavoritos.forEach(function(favorito){
        if(favorito.link != link)
            listaAux.push(favorito);
    });
    return listaAux;
}

function isFavoritos(link){
    var listaFavoritos = localStorage.getItem('ecommerce-favoritos');
    var retorno = false;

    if(listaFavoritos != null){
        listaFavoritos = JSON.parse(listaFavoritos);
        listaFavoritos.forEach(function(favorito){
            if(favorito.link == link) {
                retorno = true;
                return;
            }
        });
    }
    return retorno;
}

function removerFavoritoClick(link){
    var listaFavoritos = localStorage.getItem('ecommerce-favoritos');

    if(listaFavoritos != null) {
        listaFavoritos = JSON.parse(listaFavoritos);
        listaFavoritos = removerFavorito(link, listaFavoritos);

        localStorage.setItem('ecommerce-favoritos', JSON.stringify(listaFavoritos));

        $('a[href="'+link+'"]').parent().remove();
    }
}
<a href="/admin/reset/index.html">
    <div class="itemmenu">
        Remise à zéro
    </div>
</a>
<a href="/admin/settings/index.html">
    <div class="itemmenu">
        Configuration
    </div>
</a>
<a href="/admin/statistics/index.html">
    <div class="itemmenu">
        Statistiques
    </div>
</a>
<a href="/admin/retrieval/index.html">
    <div class="itemmenu">
        Récupération de données
    </div>
</a>
<a href="/admin/mcq/index.html">
    <div class="itemmenu">
        Notes
    </div>
</a>
<a href="/admin/home/edit.html">
    <div class="itemmenu">
        Edition
    </div>
</a>
<a href="/admin/home/showInfos.html">
    <div class="itemmenu">
        Informations
    </div>
</a>
<a href="/admin/home/index.html">
    <div class="itemmenu">
        Accueil
    </div>
</a>
<a href="/home/index.html">
    <div class="itemmenu">
        Partie utilisateur
    </div>
</a>

<script>
$(document).ready(function() {
    $(function() {
        $(".itemmenu")
            .mouseover(function() { 
            $(this).attr("class", 'overitemmenu');
        })
            .mouseout(function() {
            $(this).attr("class", 'itemmenu');
        });
    });
});
</script>

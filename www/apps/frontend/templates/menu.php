<a href="/admin/home/index.html">
    <div class="itemmenu">
        Administration
    </div>
</a>
<a href="/reports/index.html">
    <div class="itemmenu">
        <?php echo $TEXT['Menu_Reports']; ?>
    </div>
</a>
<a href="/mcq/index.html">
    <div class="itemmenu">
        <?php echo $TEXT['Menu_MCQ']; ?>
    </div>
</a>
<a href="/viewer/index.html">
    <div class="itemmenu">
        <?php echo $TEXT['Menu_Viewer']; ?>
    </div>
</a>
<a href="/lectures/index.html">
    <div class="itemmenu">
        <?php echo $TEXT['Menu_Lectures']; ?>
    </div>
</a>
<a href="/home/guide.html">
    <div class="itemmenu">
        <?php echo $TEXT['Menu_Guide']; ?>
    </div>
</a>
<a href="/home/index.html">
    <div class="itemmenu">
        <?php echo $TEXT['Menu_Home']; ?>
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

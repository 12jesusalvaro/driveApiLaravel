
<!-- resources/views/drive/view-file.blade.php -->

<div id="fileViewerModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <iframe src="https://drive.google.com/file/d/{{ $filename }}/preview" width="80%" height="400"></iframe>
            </div>
        </div>
    </div>
</div>


<!--
<script>
    $(document).ready(function() {
        var filename = "{{ $filename }}";
        var iframeSrc = 'https://drive.google.com/file/d/' + filename + '/preview';

        var iframe = $('<iframe>', {
            src: iframeSrc,
            width: '100%',
            height: 600
        });
        $('#iframeContainer').html(iframe);
    });
</script>
-->


<!--
<div class="modal fade" id="fileViewerModal" tabindex="-1" role="dialog" aria-labelledby="fileViewerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">

                <div id="iframeContainer"></div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#fileViewerModal').on('show.bs.modal', function () {
            var fileId = '{{ $filename }}'; // Obtén el ID del archivo de la variable pasada por el controlador
            var iframeSrc = 'https://drive.google.com/file/d/' + fileId + '/view';

            var iframe = $('<iframe>', {
                src: iframeSrc,
                width: '100%',
                height: 600
            });
            $('#iframeContainer').html(iframe);
        });
    });
</script>
-->

<script>
    $(function () {

      'use strict';
      
      var table = $('#userTable').DataTable({
          processing: true,
          serverSide: true,
          responsive : true,
          ajax: {
          url: "{{ route('users.index') }}",
            data: function (d) {
                  d.filter = $('.filter').val()
              }
          },
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'image', name: 'image'},
              {data : 'detail', name: 'detail'},
              {data : 'timestamp', name : 'timestamp', searchable : false},
              {data : 'status', name: 'status'},
              {data : 'action', data : 'action'}       
          ],
          dom : 'lBfrtip',
          buttons : [
            'csv','excel','pdf','print','colvis'
          ],
          order : [[0,'desc']]
      });

      $('.filter').on('change',function(){
        table.draw();
      });
      
    });
  </script>
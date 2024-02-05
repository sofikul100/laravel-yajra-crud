<x-app-layout>
  <x-slot name="header">
    <h2
      class="text-primary"
      style="font-size: 20px; text-transform: uppercase; font-weight: bold"
    >
      {{ __('Task Application') }}
    </h2>
  </x-slot>

  <!-- add new task modal -->

  <!-- Modal -->
  <div
    class="modal fade"
    id="addnewtask"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="staticBackdropLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h1 class="modal-title fs-5 text-white" id="staticBackdropLabel">
            ADD NEW TASK FORM
          </h1>
          <button
            type="button"
            class="btn-close text-white"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <form id="task__add__form">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label for="name">Task Name : </label>
              <input
                type="text"
                class="form-control"
                id="name"
                placeholder="Task Name..."
              />
            </div>
            <div id="name_error" class="mt-1"></div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-success d-flex align-items-start justify-content-start mb-3"
              style="margin-left: 14px"
            >
              Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- end -->

  <!-- Edit task modal -->

  <!-- Modal -->
  <div
    class="modal fade"
    id="edittask"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="staticBackdropLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h1 class="modal-title fs-5 text-white" id="staticBackdropLabel">
            EDIT TASK FORM
          </h1>
          <button
            type="button"
            class="btn-close text-white"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <form id="task__update__form">
          <div class="modal-body">
            <input type="hidden" name="task_id" id="task_id" />
            <div class="form-group">
              <label for="edited_name">Task Name : </label>
              <input
                type="text"
                class="form-control"
                id="edited_name"
                placeholder="Task Name..."
              />
            </div>
            <div id="name_edit_error" class="mt-1"></div>
          </div>
          <div class="">
            <button
              type="submit"
              class="btn btn-success d-flex align-items-start justify-content-start mb-3"
              style="margin-left: 14px"
            >
              Update
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- end -->

  <div class="container mt-4">
    <div class="row">
      <div class="col-md-12">
        <div class="card shadow">
          <div class="card-header py-3 d-flex justify-content-between">
            <button
              class="btn-success btn btn-sm"
              data-bs-toggle="modal"
              data-bs-target="#addnewtask"
            >
              Add New
            </button>
            <!-- filter task content -->
            <div class="d-flex gap-4">
              <div>
                <select
                  name="date_filter"
                  id="date_filter"
                  class="form-select rounded border-gray-200"
                  style="width: 272px"
                >
                  <option value="" selected>All Dates</option>
                  <option value="today">Today Task</option>
                  <option value="yesterday">Yesterday Task</option>
                  <option value="last_week">Last Week Task</option>
                  <option value="this_week">This Week Task</option>
                  <option value="last_month">Last Month Task</option>
                  <option value="this_month">This Month Task</option>
                  <option value="last_year">Last Year Task</option>
                  <option value="this_year">This Year Task</option>
                </select>
              </div>

              <div>
                <select
                  name="status_filter"
                  id="status_filter"
                  class="form-select rounded border-gray-200"
                  style="width: 272px"
                >
                  <option value="" selected>All</option>
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
            </div>
          </div>
          <div class="card-body">
            <table class="table table-bordered" id="task_table">
              <thead>
                <tr>
                  <th class="bg-dark text-white">SL</th>
                  <th class="bg-dark text-white">TASK NAME</th>
                  <th class="bg-dark text-white">STATUS</th>
                  <th class="bg-dark text-white">ACTION</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script type="text/javascript">
    $(function task() {
      task_table = $("#task_table").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('task.index') }}",
          data: function (e) {
            e.date_filter = $("#date_filter").val();
            e.status_filter = $("#status_filter").val();
          },
        },
        columns: [
          { data: "DT_RowIndex", name: "DT_RowIndex" },
          { data: "name", name: "name" },
          { data: "status", name: "status" },
          { data: "action", name: "action", orderable: true, searchable: true },
        ],
      });
    });
  </script>

  <script>
    $("#status_filter").change(function () {
      task_table.draw();
      $("#task_table").DataTable().ajax.reload();
    });

    $("#date_filter").change(function () {
      task_table.draw();
    });
  </script>

  <script>
    //working with swith=/
    $("body").on("change", "#statusSwitch", function () {
      var status = $("#statusSwitch").prop("checked") == true ? 1 : 0;
      var task_id = $(this).data("id");
      $.ajax({
        type: "GET",
        dataType: "json",
        url: '{{route("change.status")}}',
        data: { status: status, task_id: task_id },
        success: function (response) {
          if (response.success == true) {
            $("#task_table").DataTable().ajax.reload();
            toastr.success(response.message);
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    });
  </script>

  <!-- add new task logic here -->
  <script>
    $("#task__add__form").submit(function (e) {
      e.preventDefault();
      var name = $("#name").val();
      $.ajax({
        type: "POST",
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        dataType: "json",
        url: '{{route("task.store")}}',
        data: { name: name },
        success: function (response) {
          if (response.success == true) {
            $(".modal").modal("hide");
            $("#task_table").DataTable().ajax.reload();
            $("#task__add__form")[0].reset();
            $("#name_error").find("small:first").remove();
            toastr.success(response.message);
          }
        },
        error: function (error) {
          console.log(error);
          $("#name_error").html(
            `<small class="text-danger">${error.responseJSON.errors.name ? error.responseJSON.errors.name : ""}</small>`,
          );
        },
      });
    });
  </script>

  <!-- edit task logic here -->

  <script>
    $("body").on("click", ".edit_task", function () {
      var id = $(this).data("id");
      $.ajax({
        type: "GET",
        url: `task/${id}/edit`,
        success: function (response) {
          $("#edited_name").val(response.name);
          $("#task_id").val(response.id);
        },
        error: function (error) {
          console.log(error);
        },
      });
    });
  </script>

  <!-- update task logic here -->
  <script>
    $("#task__update__form").submit(function (e) {
      e.preventDefault();
      var id = $("#task_id").val();
      var name = $("#edited_name").val();
      $.ajax({
        type: "POST",
        url: '{{route("task.update")}}',
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
          id: id,
          name: name,
        },
        success: function (response) {
          if (response.success == true) {
            console.log(response);
            $(".modal").modal("hide");
            toastr.success(response.message);
            $("#task_table").DataTable().ajax.reload();
            $("#task__update__form")[0].reset();
            $("#name_edit_error").find("small:first").remove();
          }
        },
        error: function (error) {
          $("#name_edit_error").html(
            `<small class="text-danger">${error.responseJSON.errors.name ? error.responseJSON.errors.name : ""}</small>`,
          );
        },
      });
    });
  </script>

  <script>
    //==========for delete single task====//
    function deleteTask(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true,
      }).then((result) => {
        if (result.value) {
          if (result.isConfirmed) {
            $.ajax({
              headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
              },
              url: `task/${id}`,
              data: { id: id },
              type: "delete",
              success: function (response) {
                if (response.success == true) {
                  toastr.success(response.message);
                  $("#task_table").DataTable().ajax.reload();
                }
              },
              error: function (error) {
                console.log(error);
              },
            });
          }
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          Swal.fire("Cancelled", "Your data is safe :)", "error");
        }
      });
    }
  </script>
</x-app-layout>

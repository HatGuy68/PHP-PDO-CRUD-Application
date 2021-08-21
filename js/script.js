function pagination(totalpages, currentpage) {
    var pagelist = "";
    if (totalpages > 1) {
        currentpage = parseInt(currentpage);
        pagelist += `<ul class="pagination justify-content-center">`;
        const prevClass = currentpage == 1 ? " disabled" : "";
        pagelist += `<li class="page-item${prevClass}"><a class="page-link" href="#" data-page="${currentpage - 1
            }">Previous</a></li>`;
        for (let p = 1; p <= totalpages; p++) {
            const activeClass = currentpage == p ? " active" : "";
            pagelist += `<li class="page-item${activeClass}"><a class="page-link" href="#" data-page="${p}">${p}</a></li>`;
        }
        const nextClass = currentpage == totalpages ? " disabled" : "";
        pagelist += `<li class="page-item${nextClass}"><a class="page-link" href="#" data-page="${currentpage + 1
            }">Next</a></li>`;
        pagelist += `</ul>`;
    }

    $("#pagination").html(pagelist);
}

function getPlayerRow(player) {
    var playerRow = '';
    if (player) {
        playerRow = `<tr>
        <td class="align-middle text-center"></td>
        <td class="align-middle">${player.player_name}</td>
        <td class="align-middle">${player.player_score}</td>
        <td class="align-middle">
          <a href="#" class="btn btn-warning mr-3 edituser" data-toggle="modal" data-target="#userModal"
            title="Edit" data-id="${player.player_id}"><i class="fa fa-pencil-square-o fa-lg"></i></a>
          <a href="#" class="btn btn-danger deleteuser" data-userid="14" title="Delete" data-id="${player.player_id}"><i
              class="fa fa-trash-o fa-lg"></i></a>
        </td>
      </tr>`;
    }
    return playerRow;
}

function getPlayers() {
    var page_no = $('#currentpage').val();
    console.log(page_no);

    $.ajax({
        url: "./ajax.php",
        type: "GET",
        dataType: "json",
        data: { page: page_no, action: "getusers" },
        beforeSend: function () {
            $('#overlay').fadeIn();
        },
        success: function (rows) {
            if (rows.players) {
                var player_table = '';
                $.each(rows.players, function (index, player) {
                    player_table += getPlayerRow(player)
                })
                $('#players_table tbody').html(player_table);
                let totalPlayers = rows.count;
                let totalpages = Math.ceil(parseInt(totalPlayers) / 4);
                const currentpage = $('#currentpage').val();
                pagination(totalpages, currentpage);
                $('#overlay').fadeOut();
            }
        },
        error: function () {
            console.log("Oops! Something went wrong!");
        }
    })
}

$(document).ready(function () {

    $(document).on("submit", "#addform", function (event) {
        event.preventDefault();
        $.ajax({
            url: "./ajax.php",
            type: "POST",
            dataType: "json",
            data: new FormData(this),
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('#overlay').fadeIn();
            },
            success: function (response) {
                console.log(response);
                if (response) {
                    $("#userModal").modal("hide");
                    $("#addform")[0].reset();
                    $('#overlay').fadeOut();
                    getPlayers();
                }
            },
            error: function () {
                console.log("Oops! Something went wrong!");
            }
        });
    });

    $(document).on('click', 'ul.pagination li a', function (e) {
        e.preventDefault();
        var $this = $(this);
        const pageNum = $this.data('page');
        $('#currentpage').val(pageNum);
        getPlayers();
        $this.parent().siblings().removeClass("active");
        $this.parent().addClass("active");
    });

    $(document).on("click", "#addnewbtn", function (e) {
        e.preventDefault();
        $("#addform")[0].reset();
        $("#userid").val('');
    });

    $(document).on("click", "a.edituser", function (e) {
        e.preventDefault();
        var player_id = $(this).data("id");

        $.ajax({
            url: "./ajax.php",
            type: "GET",
            dataType: "json",
            data: { id: player_id, action: "getuser" },
            beforeSend: function () {
                $("#overlay").fadeIn();
            },
            success: function (player) {
                if (player) {
                    $("#player_name").val(player.player_name);
                    $("#score").val(player.player_score);
                    $("#userid").val(player.player_id);
                }
                $("#overlay").fadeOut();
            },
            error: function () {
                console.log("something went wrong");
            },
        });
    });

    $(document).on("click", "a.deleteuser", function (e) {
        e.preventDefault();
        var player_id = $(this).data("id");
        if (confirm("Are you sure you want to delete this?")) {
            $.ajax({
                url: "./ajax.php",
                type: "GET",
                dataType: "json",
                data: { id: player_id, action: "deleteuser" },
                beforeSend: function () {
                    $("#overlay").fadeIn();
                },
                success: function (res) {
                    if (res.deleted == 1) {
                        getPlayers();
                        $("#overlay").fadeOut();
                    }
                },
                error: function () {
                    console.log("something went wrong");
                },
            });
        };
    });

    getPlayers();
});
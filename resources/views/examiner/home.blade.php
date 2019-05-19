@extends('layouts.app')

@section('content')

    <div class="container" id="examinerHome">
        <div v-if="works.length != 0">
            <p style="margin-top: 20px" align="center">Работы</p>
            <table class="table table-sm">
                <thead>
                <tr>
                    <th scope="col">Имя студента</th>
                    <th scope="col">Имя руководителя</th>
                    <th scope="col">Тема на английском</th>
                    <th scope="col">Тема на украинском</th>
                    <th scope="col">Дата защиты</th>
                    <th scope="col">Известить о проблеме</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in works">
                    <td>@{{item.studName}}</td>
                    <td>@{{item.leaderName}}</td>
                    <td>@{{item.themeEn}}</td>
                    <td>@{{item.themeUkr}}</td>
                    <td>@{{item.date}}</td>
                    <td>
                        <div>
                            <div style="display: inline-block;margin-left: 10px;" v-show="!item.edit" @click="item.edit=!item.edit"
                                 class="btn-sm btn-primary"><i
                                        class="fa fa-pencil" aria-hidden="true"></i></div>
                            <div v-show="item.edit" class="input-group mb-3">
                                <input v-model="item.newNote" type="text" class="form-control" aria-label="Name"
                                       aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button @click="send(item)" class="btn btn-outline-secondary" type="button"
                                            id="button-addon2">ОК
                                    </button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div v-else>
            <span>Работ пока нет</span>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var vExaminer = new Vue({
            el: '#examinerHome',
            data: {
                works: [],
            },
            methods: {
                send: function (item) {
                    if (item.newNote!=="") {
                        let data = {data: item}
                        this.$http.post('/api/makeExaminerNote', data)
                            .then(function () {
                                alert("Готово!");
                            });
                    }
                    item.edit = false;
                },

            },
            created: function () {
                var _this = this;
                this.$http.get('/api/getExaminerWorks')
                    .then(function (response) {
                        _this.works = response.data.works;
                    });
            }
        });
    </script>
@endpush
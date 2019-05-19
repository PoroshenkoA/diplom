@extends('layouts.app')

@section('content')

    <div class="container" id="leaderHome">
        <div class="row justify-content-center">
            <div class="btn-group btn-group-lg" role="group" aria-label="ex">
                <h1>Я:&nbsp;</h1>
                <button type="button" @click="likeNormal=true" class="btn btn-secondary">Руководитель</button>
                <button type="button" @click="getLikeExaminer" class="btn btn-secondary">Член ЭК</button>
            </div>
        </div>
        <div v-if="type.userTypeID===2 || likeNormal" style="margin-top: 20px">
            <div v-if="students.length != 0">
                <div>
                    <p align="center">Мои студенты</p>
                    <table v-if="students.length != 0" class="table table-sm">
                        <thead>
                        <tr>
                            <th scope="col">Имя</th>
                            <th scope="col">Приоритет</th>
                            <th scope="col">Виза</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item,key) in students">
                            <td>
                                <div style="display: inline-block;">@{{item.name}}</div>
                            </td>
                            <td>
                                <select v-model="item.newPrior" class="custom-select" id="inputGroupSelect01">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            </td>
                            <td>
                                <label class="radio-inline"><input type="radio" v-model="item.editVisa"
                                                                   value="true"
                                                                   :name="item.name">&nbsp;<i class="fa fa-check-circle"
                                                                                              style="color: #38c172"
                                                                                              aria-hidden="true"></i></label>
                                <label style="margin-left: 30px" class="radio-inline"><input type="radio"
                                                                                             v-model="item.editVisa"
                                                                                             value="false"
                                                                                             :name="item.reqID">&nbsp;<i
                                            class="fa fa-times-circle" style="color: #e3342f;"
                                            aria-hidden="true"></i></label>
                            </td>

                        </tbody>
                    </table>
                </div>
                <div @click="send" class="btn btn-primary">Сохранить</div>
            </div>
            <div style="margin-left: 10%; margin-top: 20px;" v-if="students.length == 0 && works.length == 0">
                Запросов от студентов пока нет.
            </div>
            <div v-if="works.length != 0">
                <p style="margin-top: 20px" align="center">Работы</p>
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th scope="col">Имя студента</th>
                        <th scope="col">Тема на английском</th>
                        <th scope="col">Тема на украинском</th>
                        <th scope="col">Дата защиты</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in works">
                        <td>@{{item.studName}}</td>
                        <td>
                            <span v-show="!item.editThemeEn">@{{item.themeEn}}</span>
                            <div style="display: inline-block;margin-right: 20px; float: right;">
                                <div v-show="!item.editThemeEn" @click="item.editThemeEn=true"
                                     class="btn-sm btn-primary"><i
                                            class="fa fa-pencil" aria-hidden="false"></i>
                                </div>
                            </div>
                            <div v-show="item.editThemeEn" class="input-group mb-3">
                                <input v-model="item.newThemeEn" type="text" class="form-control" aria-label="Name"
                                       aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button @click="editEn(item)" class="btn btn-outline-secondary" type="button"
                                            id="button-addon2">ОК
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span v-show="!item.editThemeUkr">@{{item.themeUkr}}</span>
                            <div style="display: inline-block;margin-right: 20px; float: right;">
                                <div v-show="!item.editThemeUkr" @click="item.editThemeUkr=true"
                                     class="btn-sm btn-primary"><i
                                            class="fa fa-pencil" aria-hidden="false"></i>
                                </div>
                            </div>
                            <div v-show="item.editThemeUkr" class="input-group mb-3">
                                <input v-model="item.newThemeUkr" type="text" class="form-control" aria-label="Name"
                                       aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button @click="editUkr(item)" class="btn btn-outline-secondary" type="button"
                                            id="button-addon2">ОК
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td>@{{item.date}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-if="likeNormal===false">
            <div v-if="worksEx.length != 0">
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
                    <tr v-for="item in worksEx">
                        <td>@{{item.studName}}</td>
                        <td>@{{item.leaderName}}</td>
                        <td>@{{item.themeEn}}</td>
                        <td>@{{item.themeUkr}}</td>
                        <td>@{{item.date}}</td>
                        <td>
                            <div>
                                <div style="display: inline-block;margin-left: 10px;" v-show="!item.edit"
                                     @click="item.edit=!item.edit"
                                     class="btn-sm btn-primary"><i
                                            class="fa fa-pencil" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div v-show="item.edit" class="input-group mb-3">
                                <input v-model="item.newNote" type="text" class="form-control" aria-label="Name"
                                       aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button @click="sendExaminerNote(item)" class="btn btn-outline-secondary"
                                            type="button"
                                            id="button-addon2">ОК
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div style="margin-top: 20px;" align="center">
            Оповещения
        </div>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Дата</th>
                <th scope="col">Сообщение</th>
                <th scope="col">Удалить</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in text">
                <td>@{{item.date}}</td>
                <td>@{{item.text}}</td>
                <td>
                    <div style="display: inline-block; float" @click="hideNote(item)"
                         class="btn-sm btn-danger"><i
                                class="fa fa-times" aria-hidden="true"></i>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div style="display: inline-block; float:right; width: 90px" @click="hideAllNotes()"
                         class="btn-sm btn-danger">Удалить все
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        var vLeader = new Vue({
            el: '#leaderHome',
            data: {
                text: [],
                students: [],
                works: [],
                worksEx: [],
                type: "2",
                likeNormal: null,
            },
            methods: {
                hideNote: function (item) {
                    if (item.userID !== 1) {
                        var _this = this;
                        let data = {data: item};
                        this.$http.post('/api/hideNote', data).then(function (response) {
                            _.forEach(this.text, function (item1, key) {
                                if (item1.id === item.id)
                                    _this.$delete(_this.text, key)
                            });
                        });
                    } else alert("Нельзя удалить сообщение администратора");
                },
                editEn: function (item) {
                    if (item.newThemeEn !== "" && item.newThemeEn !== item.themeEn) {
                        let data = {data: item}
                        this.$http.post('/api/leaderChangeThemeEn', data)
                            .then(function () {
                                alert("Готово!");
                                item.themeEn = item.newThemeEn;
                            });
                    }
                    item.editThemeEn = false;
                },
                editUkr: function (item) {
                    if (item.newThemeUkr !== "" && item.newThemeUkr !== item.themeUkr) {
                        let data = {data: item}
                        this.$http.post('/api/leaderChangeThemeUkr', data)
                            .then(function () {
                                alert("Готово!");
                                item.themeUkr = item.newThemeUkr;
                            });
                    }
                    item.editThemeUkr = false;
                },
                hideAllNotes: function () {
                    var _this = this;
                    this.$http.get('/api/hideAllNotes').then(function (response) {
                        for (var i = _this.text.length - 1; i >= 0; i--) {
                            if (_this.text[i].userID !== 1)
                                _this.$delete(_this.text, i)
                        }
                    });
                },
                send: function () {
                    alert("Готово!");
                    var _this = this;
                    let arr = [];
                    for (var item in this.students) {
                        arr.push(this.students[item]);
                    }
                    let data = {data: arr};
                    this.$http.post('/api/updateLeaderPriority', data).then(function () {
                        _this.$http.get('/api/getStudents')
                            .then(function (response) {
                                _this.students = response.data.requests;
                            });
                        _this.$http.get('/api/getLeaderWorks')
                            .then(function (response) {
                                _this.works = response.data.works;
                            });
                    })
                },
                sendExaminerNote: function (item) {
                    if (item.newNote !== "") {
                        let data = {data: item}
                        this.$http.post('/api/makeExaminerNote', data)
                            .then(function () {
                                alert("Готово!");
                            });
                    }
                    item.edit = false;
                },
                getLikeExaminer: function () {
                    this.likeNormal = false;
                    var _this = this;
                    this.$http.get('/api/getExaminerWorks')
                        .then(function (response) {
                            _this.worksEx = response.data.works;
                        });
                },

            },
            created: function () {
                var _this = this;
                this.$http.get('/api/notifications')
                    .then(function (response) {
                        _this.text = response.data.notifications;
                    });
                this.$http.get('/api/getStudents')
                    .then(function (response) {
                        _this.students = response.data.requests;
                    });
                this.$http.get('/api/getLeaderWorks')
                    .then(function (response) {
                        _this.works = response.data.works;
                    });
                this.$http.get('/api/getLeaderType')
                    .then(function (response) {
                        _this.type = response.data.type;
                    });
            }
        });
    </script>
@endpush
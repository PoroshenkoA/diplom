@extends('layouts.app')

@section('content')

    <div style="width: 1700px" class="container" id="ad">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div @click="showLog=!showLog" class="btn btn-primary">Лог</div>
                <div style="margin-top: 10px">
                    <div v-show="showLog">
                        <div>
                            <p></p>
                            <div style="margin-left: 20px" class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">По дате</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Введите дату" aria-label="Username"
                                       aria-describedby="basic-addon1" v-model="date">
                                <div class="input-group-append">
                                    <button @click="getOnDate" class="btn btn-outline-secondary" type="button"
                                            id="button-addon2">Получить
                                    </button>
                                </div>
                            </div>
                            <p></p>
                            <div style="margin-left: 20px" class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">По пользователю</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Введите почту"
                                       aria-label="Username"
                                       aria-describedby="basic-addon1" v-model="name">
                                <div class="input-group-append">
                                    <button @click="getOnName" class="btn btn-outline-secondary" type="button"
                                            id="button-addon2">Получить
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div style="margin-left: 30px" v-if="user.name&&!showWhat">
                                <div v-if="hideName">
                                    <div class="input-group mb-3">
                                        <input v-model="newName" type="text" class="form-control"
                                               :placeholder="user.name"
                                               aria-label="Name" aria-describedby="button-addon2">
                                        <div class="input-group-append">
                                            <button @click="editName" class="btn btn-outline-secondary" type="button"
                                                    id="button-addon2">ОК
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <span v-if="!hideName">Имя пользователя: @{{ user.name }}<div
                                            style="margin-left:20px;display: inline-block;"
                                            @click="hideName=!hideName"
                                            class="btn-sm btn-primary"><i
                                                class="fa fa-pencil" aria-hidden="true"></i></div></span><br>
                                <div v-if="hideEmail">
                                    <div class="input-group mb-3">
                                        <input v-model="newEmail" type="text" class="form-control"
                                               :placeholder="user.email" aria-label="Name"
                                               aria-describedby="button-addon2">
                                        <div class="input-group-append">
                                            <button @click="editEmail" class="btn btn-outline-secondary" type="button"
                                                    id="button-addon2">ОК
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <span v-if="!hideEmail">Почта: @{{ user.email }}<div
                                            style="margin-left:20px; display: inline-block;"
                                            @click="hideEmail=!hideEmail"
                                            class="btn-sm btn-primary"><i
                                                class="fa fa-pencil" aria-hidden="true"></i></div></span><br>
                                <span>Университет: @{{ department.unName }}</span><br>
                                <span>Кафедра: @{{ department.depName }}</span><br>
                                <span v-if="user.leaderLoad">Нагрузка: @{{ user.leaderLoad }}</span>
                                <span v-if="user.userTypeID === 1">Роль: Студент</span>
                                <span v-if="user.userTypeID === 2">Роль: Руководитель</span>
                                <span v-if="user.userTypeID === 3">Роль: Член ЭК</span>
                                <span v-if="user.userTypeID === 4">Роль: Админ</span>
                                <div v-if="requests.length != 0">
                                    <p style="margin-top: 20px" align="center">Запросы</p>
                                    <table class="table table-sm">
                                        <thead>
                                        <tr>
                                            <th scope="col">Имя студента</th>
                                            <th scope="col">Имя руководителя</th>
                                            <th scope="col">Приоритет студента</th>
                                            <th scope="col">Приоритет руководителя</th>
                                            <th scope="col">Наличие визы</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="item in requests">
                                            <td>@{{item.studName}}</td>
                                            <td>@{{item.leaderName}}</td>
                                            <td>@{{item.studentPriority}}</td>
                                            <td>@{{item.leaderPriority}}</td>
                                            <td><span v-if="item.visa"><i class="fa fa-check-circle"
                                                                          style="color: #38c172"
                                                                          aria-hidden="true"></i></span><span v-else><i
                                                            class="fa fa-times-circle" style="color: #e3342f;"
                                                            aria-hidden="true"></i></span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

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
                                            <th scope="col">Удалить</th>
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
                                                <div style="display: inline-block;" @click="del(item)"
                                                     class="btn-sm btn-danger"><i
                                                            class="fa fa-times" aria-hidden="true"></i></div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div v-if="nots.length != 0">
                                <p style="margin-top: 30px" align="center">Оповещения</p>
                                <div style="margin-left: 30px" class="card" style="width: 80%;">
                                    <div class="card-header">
                                        <span v-if="showWhat">@{{ date }}</span>
                                        <div v-else><span>@{{ name }}</span>
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item" v-for="item in nots"><strong>@{{item.date}}</strong>&nbsp;@{{item.text}}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p></p>
                    <div @click="hideNote=!hideNote" class="btn btn-primary">Новое оповещение</div>
                    <div style="margin-left: 30px; margin-top: 10px;" v-show="hideNote" class="input-group mb-3">
                        <input v-model="newNote" type="text" class="form-control" aria-label="Name"
                               aria-describedby="button-addon2">
                        <div class="input-group-append">
                            <button @click="makeNewNote" class="btn btn-outline-secondary" type="button"
                                    id="button-addon2">ОК
                            </button>
                        </div>
                    </div>
                    <p></p>
                    <div @click="chooseYourDestiny" class="btn btn-primary">Распределить все запросы</div>
                    </table>
                </div>
            </div>
        </div>
        @endsection

        @push('scripts')
            <script>
                var vAd = new Vue({
                    el: '#ad',
                    data: {
                        showLog: false,
                        name: '',
                        date: '',
                        nots: [],
                        showWhat: true,
                        user: [],
                        requests: [],
                        works: [],
                        department: [],
                        hideName: false,
                        hideEmail: false,
                        hideNote: false,
                        newName: '',
                        newEmail: '',
                        newNote: '',
                    },
                    methods: {
                        chooseYourDestiny: function () {
                            this.$http.get('/api/adminMakeWorks')
                                .then(function () {
                                    alert("Готово!");
                                });
                        },
                        makeNewNote: function () {
                            let data = {text: this.newNote}
                            this.$http.post('/api/makeAdminNote', data)
                                .then(function () {
                                    alert("Готово!");
                                });
                        },
                        del: function (item) {
                            var _this = this;
                            let data = {
                                data: item.id, studName: item.studName, leaderName: item.leaderName,
                                studID: item.studID, leaderID: item.leaderID
                            };
                            this.$http.post('/api/deleteWork', data).then(function (response) {
                                _.forEach(this.works, function (item1, key) {
                                    if (item1.id === item.id)
                                        _this.$delete(_this.works, key)
                                });
                            });
                        },
                        editName: function () {
                            if (this.newName === '' || this.newName === this.user.name) {
                                this.hideName = false;
                                this.newName = this.user.name;
                                return;
                            }
                            var _this = this;
                            let data = {data: this.newName, id: this.user.id};
                            this.$http.post('/api/adminNewUsername', data)
                                .then(function () {
                                    _this.user.name = _this.newName;
                                    _this.hideName = false;
                                });
                        },
                        editEmail: function () {
                            if (this.newEmail === '' || this.newEmail === this.user.email) {
                                this.hideEmail = false;
                                this.newEmail = this.user.email;
                                return;
                            }
                            var _this = this;
                            let data = {data: this.newEmail, id: this.user.id};
                            this.$http.post('/api/adminNewEmail', data)
                                .then(function () {
                                    _this.user.email = _this.newEmail;
                                    _this.hideEmail = false;
                                });
                        },
                        getOnDate: function () {
                            this.nots = [];
                            this.showWhat = true;
                            var _this = this;
                            this.$http.get('/api/adminDateNotes/' + this.date)
                                .then(function (response) {
                                    _this.nots = response.data.notifications;
                                });
                        },
                        getOnName: function () {
                            this.nots = [];
                            this.user = [];
                            this.requests = [];
                            this.works = [];
                            this.department = [];
                            this.showWhat = false;
                            var _this = this;
                            this.$http.get('/api/adminUserNotes/' + this.name)
                                .then(function (response) {
                                    _this.nots = response.data.notifications;
                                });
                            this.$http.get('/api/adminUser/' + this.name)
                                .then(function (response) {
                                    _this.user = response.data.user;
                                    _this.requests = response.data.requests;
                                    _this.works = response.data.works;
                                    _this.department = response.data.department;
                                });
                        },
                    },
                });
            </script>
    @endpush
@extends('layouts.app')

@section('content')

    <div class="container" id="notifications">

        <div class="row justify-content-center">
            <table border="0">
                <tr>
                    <div>
                        <p v-if="requests.length != 0" align="center">Мои руководители</p>
                        <table v-if="requests.length != 0" class="table table-sm">
                            <thead>
                            <tr>
                                <th scope="col">Имя</th>
                                <th scope="col">Приоритет</th>
                                <th scope="col">Наличие визы</th>
                                <th scope="col">Удалить</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item,key) in requests">
                                <td>@{{item.name}}</td>
                                <td><span v-show="!item.edit">@{{item.studentPriority}}</span>
                                    <div style="display: inline-block;" v-show="!item.edit">
                                        <div style="display: inline-block;margin-left: 10px;" @click="edit(item)"
                                             class="btn-sm btn-primary"><i
                                                    class="fa fa-pencil" aria-hidden="true"></i></div>
                                    </div>
                                    <div style="display: inline-block;" v-show="item.edit">
                                        <div style="display: inline-block;">
                                            <label class="radio-inline"><input type="radio" v-model="item.newPrior"
                                                                               value="1" :disabled="chosen_priorities.indexOf(1)!==-1
                                                                               || chosen_priorities2.indexOf('1')!==-1 "
                                                                               :name="item.name">1</label>&nbsp;
                                            <label class="radio-inline"><input type="radio" v-model="item.newPrior"
                                                                               value="2" :disabled="chosen_priorities.indexOf(2)!==-1
                                                                               || chosen_priorities2.indexOf('2')!==-1 "
                                                                               :name="item.name">2</label>&nbsp;
                                            <label class="radio-inline"><input type="radio" v-model="item.newPrior"
                                                                               value="3" :disabled="chosen_priorities.indexOf(3)!==-1
                                                                               || chosen_priorities2.indexOf('3')!==-1 "
                                                                               :name="item.name">3</label>
                                        </div>
                                        <div style="display: inline-block;margin-left: 10px;" @click="editPrior(item)"
                                             class="btn-sm btn-success"><i
                                                    class="fa fa-check" aria-hidden="true"></i></div>
                                        <div style="display: inline-block;margin-left: 10px;" @click="cancel(item)"
                                             class="btn-sm btn-danger"><i
                                                    class="fa fa-times" aria-hidden="true"></i></div>
                                    </div>
                                </td>
                                <td><span v-if="item.visa"><i class="fa fa-check-circle" style="color: #38c172"
                                                              aria-hidden="true"></i></span><span v-else><i
                                                class="fa fa-times-circle" style="color: #e3342f;"
                                                aria-hidden="true"></i></span></td>
                                <td>
                                    <div style="display: inline-block;" @click="del(item)" class="btn-sm btn-danger"><i
                                                class="fa fa-times" aria-hidden="true"></i></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </tr>
                <tr>
                    <div>
                        <p align="center">Возможные руководители</p>
                        <div class="card" style="width: 90%;">
                            <ul class="list-group list-group-flush">
                                <li style="float:left" class="list-group-item" v-for="item1 in leaders"
                                    v-if="item1.leaderLoad!==0 && item1.leaderLoad!==item1.leaderCurLoad">
                                    <div style="clear:both; text-align:right;">
                                        <div style="float:left;">@{{item1.name}}</div>
                                        <div>
                                            <label class="radio-inline"><input type="radio" v-model="item1.radio"
                                                                               value="1" :disabled="chosen_priorities.indexOf(1)!==-1
                                                                               || chosen_priorities2.indexOf('1')!==-1 "
                                                                               :name="item1.name">1</label>&nbsp;
                                            <label class="radio-inline"><input type="radio" v-model="item1.radio"
                                                                               value="2" :disabled="chosen_priorities.indexOf(2)!==-1
                                                                               || chosen_priorities2.indexOf('2')!==-1 "
                                                                               :name="item1.name">2</label>&nbsp;
                                            <label class="radio-inline"><input type="radio" v-model="item1.radio"
                                                                               value="3" :disabled="chosen_priorities.indexOf(3)!==-1
                                                                               || chosen_priorities2.indexOf('3')!==-1 "
                                                                               :name="item1.name">3</label>&nbsp;
                                            <label class="radio-inline" style="margin-right: 5%;"><input type="radio"
                                                                                                         v-model="item1.radio"
                                                                                                         value=""
                                                                                                         :name="item1.name">Нет</label>
                                        </div>
                                    </div>
                            </ul>
                        </div>
                        <div style="margin-top: 5px;" @click="send" class="btn btn-primary">Сохранить</div>
                    </div>
                </tr>
            </table>
            <div v-if="work !== false">
                <p style="margin-top: 20px" align="center">Работа</p>
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th scope="col">Имя руководителя</th>
                        <th scope="col">Тема на английском</th>
                        <th scope="col">Тема на украинском</th>
                        <th scope="col">Дата Защиты</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>@{{work[0].leaderName}}</td>
                        <td>
                            <span v-show="!editThemeEn">@{{work[0].themeEn}}</span>
                            <div style="display: inline-block;margin-right: 20px; float: right;">
                                <div v-show="!editThemeEn" @click="editThemeEn=true"
                                     class="btn-sm btn-primary" style="float: right; margin-right: 20px;"><i
                                            class="fa fa-pencil" aria-hidden="false"></i>
                                </div>
                            </div>
                            <div v-show="editThemeEn" class="input-group mb-3">
                                <input v-model="work[0].newThemeEn" type="text" class="form-control" aria-label="Name"
                                       aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button @click="editEn()" class="btn btn-outline-secondary" type="button"
                                            id="button-addon2">ОК
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span v-show="!editThemeUkr">@{{work[0].themeUkr}}</span>
                            <div style="display: inline-block;margin-right: 20px; float: right;">
                                <div v-show="!editThemeUkr" @click="editThemeUkr=true"
                                     class="btn-sm btn-primary" style="float: right; margin-right: 20px;"><i
                                            class="fa fa-pencil" aria-hidden="false"></i>
                                </div>
                            </div>
                            <div v-show="editThemeUkr" class="input-group mb-3">
                                <input v-model="work[0].newThemeUkr" type="text" class="form-control" aria-label="Name"
                                       aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button @click="editUkr()" class="btn btn-outline-secondary" type="button"
                                            id="button-addon2">ОК
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span v-show="!editDate">@{{work[0].date}}</span>
                                <div style="display: inline-block;margin-right: 20px; float: right;">
                                    <div v-show="!editDate" @click="showAvDates()"
                                         class="btn-sm btn-primary" style="float: right; margin-right: 20px;"><i
                                                class="fa fa-pencil" aria-hidden="false"></i>
                                    </div>
                                </div>
                                <div style="display: inline-block;" v-if="editDate" class="input-group mb-3">
                                    <select v-model="work[0].newDate" class="custom-select" id="inputGroupSelect01">
                                        <option></option>
                                        <option v-for="(item1,key) in avDates" :value="item1">@{{ item1 }}</option>
                                    </select>

                                    <button @click="editNewDate()" class="btn btn-primary" type="button">
                                        ОК
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 40px;" align="center">
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
                        <div style="display: inline-block;" @click="hideNote(item)"
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
    </div>
@endsection

@push('scripts')
    <script>
        var vStud = new Vue({
            el: '#notifications',
            data: {
                leaders: [],
                requests: [],
                text: [],
                work: null,
                avDates: [],
                editThemeEn: false,
                editThemeUkr: false,
                editDate: false,
            },
            computed: {
                chosen_priorities: function () {
                    let arr = [];
                    _.forEach(this.requests, function (item) {
                        arr.push(item.studentPriority);
                    });
                    return arr;
                },
                chosen_priorities2: function () {
                    let arr = [];
                    _.forEach(this.leaders, function (item) {
                        arr.push(item.radio);
                    });
                    return arr;
                }
            },
            methods: {
                send: function () {
                    let arr = [];
                    var _this = this;
                    var nums = [];
                    for (var item in this.leaders) {
                        if (this.leaders[item].radio) {
                            arr.push(this.leaders[item]);
                            nums.push(item);
                        }
                    }
                    for (var i = nums.length - 1; i >= 0; i--) {
                        this.leaders.splice(nums[i], 1);
                    }
                    if (arr.length != 0) {
                        let data = {data: arr};
                        this.$http.post('/api/createRequest', data).then(function () {
                            _this.$http.get('/api/requests')
                                .then(function (response) {
                                    _this.requests = response.data.requests;
                                });
                        })
                    }
                },
                del: function (item) {
                    if (item.visa) {
                        let data = {data: item};
                        this.$http.post('/api/makeNot', data);
                        alert("Нельзя удалить руководителя если уже есть виза. Сообщение об попытке удалить отправлено руководителю");
                    } else {
                        var _this = this;
                        let data = {data: item.reqID};
                        this.$http.post('/api/deleteRequest', data).then(function (response) {
                            _this.$http.get('/api/getLeaders')
                                .then(function (response) {
                                    _this.leaders = response.data.leaders;
                                });
                            _this.$http.get('/api/requests')
                                .then(function (response) {
                                    _this.requests = response.data.requests;
                                });
                        });
                    }
                },
                edit: function (item) {
                    item.edit = true;
                },
                cancel: function (item) {
                    item.edit = false;
                },
                editPrior: function (item) {
                    let data = {data: item.newPrior, id: item.id};
                    this.$http.post('/api/editPrior', data);
                    item.studentPriority = item.newPrior;
                    item.edit = false
                },
                editEn: function () {
                    var _this = this;
                    if (_this.work[0].newThemeEn !== "" && _this.work[0].newThemeEn !== _this.work[0].themeEn) {
                        let data = {data: _this.work[0]}
                        this.$http.post('/api/studChangeThemeEn', data)
                            .then(function () {
                                _this.work[0].themeEn = _this.work[0].newThemeEn;
                            });
                    }
                    _this.editThemeEn = false;
                },
                editUkr: function () {
                    var _this = this;
                    if (_this.work[0].newThemeUkr !== "" && _this.work[0].newThemeUkr !== _this.work[0].themeUkr) {
                        let data = {data: _this.work[0]}
                        this.$http.post('/api/studChangeThemeUkr', data)
                            .then(function () {
                                _this.work[0].themeUkr = _this.work[0].newThemeUkr;
                            });
                    }
                    _this.editThemeUkr = false;
                },
                editNewDate: function () {
                    var _this = this;
                    _this.editDate = false;
                    if (_this.work[0].newDate !== _this.work[0].date) {
                        let data = {data: _this.work[0]}
                        this.$http.post('/api/studChangeDate', data)
                            .then(function () {
                                _this.work[0].date = _this.work[0].newDate;
                            });
                    }
                },
                showAvDates: function () {
                    var _this = this;
                    _this.editDate = true;
                    this.$http.get('/api/studGetAvDates')
                        .then(function (response) {
                            _this.avDates = response.data.data;
                        });

                },
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
                hideAllNotes: function () {
                    var _this = this;
                    this.$http.get('/api/hideAllNotes').then(function (response) {
                        for (var i = _this.text.length - 1; i >= 0; i--) {
                            if (_this.text[i].userID !== 1)
                                _this.$delete(_this.text, i)
                        }
                    });
                },
            },

            created: function () {
                var _this = this;
                this.$http.get('/api/notifications')
                    .then(function (response) {
                        _this.text = response.data.notifications;
                    });
                this.$http.get('/api/work')
                    .then(function (response) {
                        _this.work = response.data.work;
                    });
                this.$http.get('/api/getLeaders')
                    .then(function (response) {
                        _this.leaders = response.data.leaders;
                    });
                this.$http.get('/api/requests')
                    .then(function (response) {
                        _this.requests = response.data.requests;
                    });
            }
        });
    </script>
@endpush

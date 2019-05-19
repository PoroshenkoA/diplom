@extends('layouts.app')

@section('content')

    <div class="container" id="leaders">
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
                                                class="fa fa-times-circle" style="color: #e3342f;" aria-hidden="true"></i></span></td>
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
                                <li style="float:left" class="list-group-item" v-for="item1 in leaders" v-if="item1.leaderLoad!==0 && item1.leaderLoad!==item1.leaderCurLoad">
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
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var vLead = new Vue({
            el: '#leaders',
            data: {
                leaders: [],
                requests: [],
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
            },
            created: function () {
                var _this = this;
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

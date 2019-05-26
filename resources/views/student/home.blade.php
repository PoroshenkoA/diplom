@extends('layouts.app')

@section('content')

    <div class="container" id="stud">

        <div v-if="typeof work[0] === 'undefined'" class="row justify-content-center">
            <td v-if="work === false">
                <table width="100%" border="0">
                    <tr width="100%">
                        <td width="100%">
                            <p v-if="requests.length != 0" align="center">Мої керівники</p>
                            <table v-if="requests.length != 0" class="table table-sm">
                                <thead>
                                <tr>
                                    <th scope="col">ПІБ</th>
                                    <th scope="col">Пріорітет</th>
                                    <th scope="col">Наявність візи</th>
                                    <th scope="col">Видалити</th>
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
                                                                                   value="1" :disabled="chosen_priorities.indexOf('1')!==-1
                                                                               || chosen_priorities2.indexOf('1')!==-1 ||
                                                                               chosen_priorities3.indexOf(1)!==-1 ||
                                                                                chosen_priorities.indexOf(3)!==-1"
                                                                                   :name="item.name">1</label>&nbsp;
                                                <label class="radio-inline"><input type="radio" v-model="item.newPrior"
                                                                                   value="2" :disabled="chosen_priorities.indexOf('2')!==-1
                                                                               || chosen_priorities2.indexOf('2')!==-1 ||
                                                                               chosen_priorities3.indexOf(2)!==-1 ||
                                                                                chosen_priorities.indexOf(2)!==-1"
                                                                                   :name="item.name">2</label>&nbsp;
                                                <label class="radio-inline"><input type="radio" v-model="item.newPrior"
                                                                                   value="3" :disabled="chosen_priorities.indexOf('3')!==-1
                                                                               || chosen_priorities2.indexOf('3')!==-1 ||
                                                                               chosen_priorities3.indexOf(3)!==-1 ||
                                                                                chosen_priorities.indexOf(3)!==-1"
                                                                                   :name="item.name">3</label>
                                            </div>
                                            <div style="display: inline-block;margin-left: 10px;"
                                                 @click="editPrior(item)"
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
                                                    aria-hidden="true"></i></span>
                                    </td>
                                    <td>
                                        <div style="display: inline-block;" @click="del(item)"
                                             class="btn-sm btn-danger"><i
                                                    class="fa fa-times" aria-hidden="true"></i></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr width="100%">
                        <td width="100%">
                            <p align="center">Можливі керівники</p>
                            <table width="100%">
                                <tr v-for="item1 in leaders"
                                    v-if="item1.leaderLoad!==0 && item1.leaderLoad!==item1.leaderCurLoad">
                                    <td width="20%"></td>
                                    <td width="50%">@{{item1.name}}</td>
                                    <td>
                                        <label class="radio-inline"><input type="radio" v-model="item1.radio"
                                                                           value="1" :disabled="chosen_priorities.indexOf('1')!==-1
                                                                               || chosen_priorities2.indexOf('1')!==-1 ||
                                                                               chosen_priorities3.indexOf(1)!==-1 ||
                                                                                chosen_priorities.indexOf(1)!==-1"
                                                                           :name="item1.name">1</label>&nbsp;
                                        <label class="radio-inline"><input type="radio" v-model="item1.radio"
                                                                           value="2" :disabled="chosen_priorities.indexOf('2')!==-1
                                                                               || chosen_priorities2.indexOf('2')!==-1 ||
                                                                               chosen_priorities3.indexOf(2)!==-1 ||
                                                                               chosen_priorities.indexOf(2)!==-1"
                                                                           :name="item1.name">2</label>&nbsp;
                                        <label class="radio-inline"><input type="radio" v-model="item1.radio"
                                                                           value="3" :disabled="chosen_priorities.indexOf('3')!==-1
                                                                               || chosen_priorities2.indexOf('3')!==-1 ||
                                                                                chosen_priorities3.indexOf(3)!==-1 ||
                                                                                chosen_priorities.indexOf(3)!==-1"
                                                                           :name="item1.name">3</label>&nbsp;
                                        <label class="radio-inline" style="margin-right: 5%;"><input
                                                    type="radio"
                                                    v-model="item1.radio"
                                                    value=""
                                                    :name="item1.name">Ні</label>
                                    </td>
                                </tr>
                            </table>
                            <div style="margin-top: 5px;margin-left: 45%" @click="send" class="btn btn-primary">
                                Зберегти
                            </div>
                        </td>
                    </tr>
                </table>
        </div>
        <div v-if="typeof work[0] !== 'undefined'">
            <p style="margin-top: 20px" align="center">Робота</p>
            <table class="table table-sm" border="0">
                <tbody>
                <tr>
                    <td width="250px">ПІБ керівника</td>
                    <td colspan="2">@{{work[0].leaderName}}</td>
                </tr>
                <tr>
                    <td>Тема англійською</td>
                    <td>
                        <span v-show="!editThemeEn">@{{work[0].themeEn}}</span>
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
                    <td width="10%">
                        <div style="display: inline-block;margin-right: 20px; float: right;">
                            <div v-show="!editThemeEn" @click="editThemeEn=true"
                                 class="btn-sm btn-primary"><i
                                        class="fa fa-pencil" aria-hidden="false"></i>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Тема українською</td>
                    <td>
                        <span v-show="!editThemeUkr">@{{work[0].themeUkr}}</span>
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
                        <div style="display: inline-block;margin-right: 20px; float: right;">
                            <div v-show="!editThemeUkr" @click="editThemeUkr=true"
                                 class="btn-sm btn-primary"><i
                                        class="fa fa-pencil" aria-hidden="false"></i>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Дата захисту</td>
                    <td>
                        <span v-show="!editDate">@{{work[0].date}}</span>
                        <div style="display: inline-block;" v-if="editDate" class="input-group mb-3">
                            <select v-model="work[0].newDate" class="custom-select" id="inputGroupSelect01">
                                <option></option>
                                <option v-for="(item1,key) in avDates" :value="item1">@{{ item1 }}</option>
                            </select>

                            <button @click="editNewDate()" class="btn btn-primary" type="button">
                                ОК
                            </button>
                        </div>
                    </td>
                    <td>
                        <div style="display: inline-block;margin-right: 20px; float: right;">
                            <div v-show="!editDate" @click="showAvDates()"
                                 class="btn-sm btn-primary"><i
                                        class="fa fa-pencil" aria-hidden="false"></i>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Напрацювання</td>
                    <td>
                        <div v-show="work[0].file !== null && !editFile">
                            <a id="link" style="display: inline-block;"
                               :href="'/download/'+work[0].file+'/name/'+work[0].studName"><i
                                        class="icon-download-alt"> </i>Завантажити&nbsp;</a>
                        </div>
                        <div v-show="editFile" class="input-group">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <input type="file" style="display: none" id="inputGroupFile04"
                                       @change="onFileChange">
                                <label for="inputGroupFile04" class="btn btn-secondary">Обрати</label>
                                <label type="button" @click="sendFile" class="btn btn-secondary">ОК</label>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="display: inline-block;margin-right: 20px; float: right;">
                            <div v-show="!editFile" @click="editFile=true"
                                 class="btn-sm btn-primary"><i
                                        class="fa fa-pencil" aria-hidden="false"></i>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Кількість сторінок</td>
                    <td>
                        <span v-show="!editRealPages">@{{work[0].realPages}}</span>
                        <div v-show="editRealPages" class="input-group mb-3">
                            <input v-model="work[0].realPages" type="text" class="form-control" aria-label="Name"
                                   aria-describedby="button-addon2">
                            <div class="input-group-append">
                                <button @click="editRP()" class="btn btn-outline-secondary" type="button"
                                        id="button-addon2">ОК
                                </button>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="display: inline-block;margin-right: 20px; float: right;">
                            <div v-show="!editRealPages" @click="editRealPages=true"
                                 class="btn-sm btn-primary" s><i
                                        class="fa fa-pencil" aria-hidden="false"></i>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Кількість слайдів</td>
                    <td>
                        <span v-show="!editPresentationPages">@{{work[0].graphicPages}}</span>
                        <div v-show="editPresentationPages" class="input-group mb-3">
                            <input v-model="work[0].graphicPages" type="text" class="form-control" aria-label="Name"
                                   aria-describedby="button-addon2">
                            <div class="input-group-append">
                                <button @click="editGP()" class="btn btn-outline-secondary" type="button"
                                        id="button-addon2">ОК
                                </button>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="display: inline-block;margin-right: 20px; float: right;">
                            <div v-show="!editPresentationPages" @click="editPresentationPages=true"
                                 class="btn-sm btn-primary"><i
                                        class="fa fa-pencil" aria-hidden="false"></i>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr v-if="work[0].r1n !== null">
                    <td>Перший рецензент</td>
                    <td>
                        <span><strong>ПІБ: </strong>@{{ work[0].r1n }}</span>
                        <p></p>
                        <span><strong>Місце роботи: </strong>@{{ work[0].r1w }}</span>
                        <p></p>
                        <span><strong>Посада: </strong> @{{ work[0].r1p }}</span>
                        <p></p>
                        <span><strong>Науковий ступ: </strong> @{{ work[0].r1d }}</span>
                    </td>
                    <td>
                        <div style="display: inline-block;margin-right: 20px; float: right;">
                            <div @click="delRev1()"
                                 class="btn-sm btn-danger"><i
                                        class="fa fa-times" aria-hidden="true"></i>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr v-if="work[0].r2n !== null">
                    <td>Другий рецензент</td>
                    <td>
                        <span><strong>ПІБ: </strong>@{{ work[0].r2n }}</span>
                        <p></p>
                        <span><strong>Місце роботи: </strong>@{{ work[0].r2w }}</span>
                        <p></p>
                        <span><strong>Посада: </strong> @{{ work[0].r2p }}</span>
                        <p></p>
                        <span><strong>Науковий ступ: </strong> @{{ work[0].r2d }}</span>
                    </td>
                    <td>
                        <div style="display: inline-block;margin-right: 20px; float: right;">
                            <div @click="delRev2()"
                                 class="btn-sm btn-danger"><i
                                        class="fa fa-times" aria-hidden="true"></i>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr v-if="work[0].questions[0]">
                    <td>Оцінки членів ЕК</td>
                    <td colspan="2">
                        <table class="table table-sm" border="0">
                            <tr>
                                <th>Екзаменатор</th>
                                <th>Питання</th>
                                <th>Оцінка</th>
                            </tr>
                            <tr v-for="ques in work[0].questions">
                                <td><strong>@{{ ques.name}}</strong></td>
                                <td>@{{ ques.question }}</td>
                                <td><h3>@{{ ques.examinerRate }}</h3></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr v-if="work[0].prot !== null">
                    <td>Номер протоколу</td>
                    <td>
                        <h3 style="margin-left: 30%">@{{work[0].prot}}</h3>
                    </td>
                </tr>
                <tr v-if="work[0].rate !== null">
                    <td>Підсумковий бал</td>
                    <td>
                        <h3 style="margin-left: 30%">@{{work[0].rate}}</h3>
                    </td>
                </tr>
                <tr v-if="work[0].rate !== null">
                    <td>У національній шкалі</td>
                    <td>
                        <h3 style="margin-left: 30%" v-if="work[0].rate<60">Незадовільно</h3>
                        <h3 style="margin-left: 30%" v-if="work[0].rate>=60 && work[0].rate<75">Задовільно</h3>
                        <h3 style="margin-left: 30%" v-if="work[0].rate>=75 && work[0].rate<90 ">Добре</h3>
                        <h3 style="margin-left: 30%" v-if="work[0].rate>=90">Відмінно</h3>
                    </td>
                </tr>
                <tr v-if="work[0].rate !== null">
                    <td>У європейскій шкалі</td>
                    <td>
                        <h3 style="margin-left: 30%" v-if="work[0].rate<60">F</h3>
                        <h3 style="margin-left: 30%" v-if="work[0].rate>=60 && work[0].rate<75">E</h3>
                        <h3 style="margin-left: 30%" v-if="work[0].rate>=75 && work[0].rate<90 ">C</h3>
                        <h3 style="margin-left: 30%" v-if="work[0].rate>=90 && work[0].rate<96 ">B</h3>
                        <h3 style="margin-left: 30%" v-if="work[0].rate>=96">A</h3>
                    </td>
                </tr>
                </tbody>
            </table>
            <div>
                    <span v-if="addRev===false && (work[0].rev1===null || work[0].rev2===null)" style="margin-top: 10px"
                          class="btn btn-outline-secondary" @click="addRev=true">
                        Додати рецензента
                    </span>
                <div v-if="addRev===true">
                    <input v-model="newRN" type="text" class="form-control" aria-label="Name"
                           aria-describedby="button-addon2" placeholder="ПІБ">
                    <input v-model="newRW" type="text" class="form-control" aria-label="Name"
                           aria-describedby="button-addon2" placeholder="Місце роботи">
                    <input v-model="newRP" type="text" class="form-control" aria-label="Name"
                           aria-describedby="button-addon2" placeholder="Посада">
                    <input v-model="newRD" type="text" class="form-control" aria-label="Name"
                           aria-describedby="button-addon2" placeholder="Науковий ступ">
                    <button style="margin-top: 10px;" @click="addNewRev()"
                            class="btn-sm btn-primary">Додати
                    </button>
                </div>
            </div>
        </div>
        <div v-if="typeof text[0] !== 'undefined'">
            <div style="margin-top: 10%;" align="center">
                Сповіщення
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Дата</th>
                    <th scope="col">Текст</th>
                    <th scope="col">Видалити</th>
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
                        <div style="display: inline-block; float:right; width: 95px" @click="hideAllNotes()"
                             class="btn-sm btn-danger">Видалити усі
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
            el: '#stud',
            data: {
                leaders: [],
                requests: [],
                text: [],
                work: false,
                file: null,
                avDates: [],
                editThemeEn: false,
                editThemeUkr: false,
                editDate: false,
                editFile: false,
                editRealPages: false,
                editPresentationPages: false,
                addRev: false,
                newRN: '',
                newRP: '',
                newRD: '',
                newRW: '',
            },
            computed: {
                chosen_priorities: function () {
                    let arr = [];
                    _.forEach(this.requests, function (item) {
                        arr.push(item.newPrior);
                    });
                    return arr;
                },
                chosen_priorities2: function () {
                    let arr = [];
                    _.forEach(this.leaders, function (item) {
                        arr.push(item.radio);
                    });
                    return arr;
                },
                chosen_priorities3: function () {
                    let arr = [];
                    _.forEach(this.requests, function (item) {
                        arr.push(item.studentPriority);
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
                    if (arr.length !== 0) {
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
                        alert("Неможливо видалити роботи якщо вже стоїть віза. Запит про видалення відправлено керівнику.");
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
                addNewRev: function () {
                    var _this = this;
                    if (this.newRD === '' || this.newRW === '' || this.newRP === '' || this.newRN === '') {
                        return alert("Заповніть усі поля");
                    }
                    if (this.work[0].rev1 && this.work[0].rev2) {
                        return alert("Нема місць");
                    }
                    let data = {
                        name: _this.newRN,
                        wp: _this.newRW,
                        d: _this.newRD,
                        p: _this.newRP,
                        leaderID: _this.work[0].leaderID
                    };
                    this.$http.post('/api/studAddNewRev', data).then(function (response) {
                        if (_this.work[0].rev1) {
                            _this.work[0].rev2 = response.data.id;
                            _this.work[0].r2n = _this.newRN;
                            _this.work[0].r2d = _this.newRD;
                            _this.work[0].r2w = _this.newRW;
                            _this.work[0].r2p = _this.newRP;
                        } else {
                            _this.work[0].rev1 = response.data.id;
                            _this.work[0].r1n = _this.newRN;
                            _this.work[0].r1d = _this.newRD;
                            _this.work[0].r1w = _this.newRW;
                            _this.work[0].r1p = _this.newRP;
                        }
                        _this.addRev = false;
                    });
                },
                editPrior: function (item) {
                    let data = {data: item.newPrior, id: item.reqID};
                    this.$http.post('/api/editPrior', data);
                    item.studentPriority = item.newPrior;
                    item.edit = false;
                },
                editRP: function () {
                    if (!isNaN(this.work[0].realPages)) {
                        let data = {data: this.work[0]}
                        this.$http.post('/api/studEditRealPages', data)
                        this.editRealPages = false;
                    } else return alert("Ошибка валидации");
                },
                delRev1: function () {
                    let data = {id: this.work[0].rev1}
                    this.$http.post('/api/studDelRev', data)
                    this.work[0].rev1 = null;
                    this.work[0].r1n = null;
                    this.work[0].r1w = null;
                    this.work[0].r1p = null;
                    this.work[0].r1d = null;
                },
                delRev2: function () {
                    let data = {id: this.work[0].rev2}
                    this.$http.post('/api/studDelRev', data)
                    this.work[0].rev2 = null;
                    this.work[0].r2n = null;
                    this.work[0].r2w = null;
                    this.work[0].r2p = null;
                    this.work[0].r2d = null;
                },
                editGP: function () {
                    if (!isNaN(this.work[0].graphicPages)) {
                        let data = {data: this.work[0]}
                        this.$http.post('/api/studEditGPages', data)
                        this.editPresentationPages = false;
                    } else return alert("Ошибка валидации");
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
                    } else alert("Неможливо видалити сповіщення адміністратора");
                },

                onFileChange(e) {
                    var files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                    this.file = files[0];
                },

                sendFile: function () {
                    if (this.file !== null) {
                        var _this = this;
                        var formData = new FormData();
                        formData.append('file', _this.file);
                        formData.append('uuid', _this.work[0].file);
                        formData.append('leaderID', _this.work[0].leaderID);
                        _this.$http.post('/api/studSendFile', formData)
                            .then(function (response) {
                                _this.editFile = false;
                                this.work[0].file = response.data.docName;
                            }).catch(function (respond) {
                            _this.editFile = false;
                            alert("Необхідний формат файлу - .docx")
                        });
                    }
                    this.editFile = false;
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

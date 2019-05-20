@extends('layouts.app')

@section('content')

    <div class="container" id="ad">
        <div>
            <div @click="showLog=!showLog" class="btn btn-primary">Лог</div>
            <div style="margin-top: 10px">
                <div v-show="showLog">
                    <div>
                        <p></p>
                        <div style="margin-left: 20px;" class="input-group mb-3">
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
                            <p>Имя пользователя: @{{ user.name }}</p>
                            <p>Почта: @{{ user.email }}</p>
                            <p>Университет: @{{ department.unName }}</p>
                            <p>Кафедра: @{{ department.depName }}</p>
                            <p v-if="group.name!=='Руководители'">Группа: @{{ group.name }}</p>
                            <p v-if="user.leaderLoad">Нагрузка: @{{ user.leaderLoad }}</p>
                            <p v-if="user.userTypeID === 1">Роль: Студент</p>
                            <p v-if="user.userTypeID === 2">Роль: Руководитель</p>
                            <p v-if="user.userTypeID === 3">Роль: Член ЭК</p>
                            <p v-if="user.userTypeID === 4">Роль: Админ</p>
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

                            <div v-if="works[0].id">
                                <p style="margin-top: 20px" align="center">Работы</p>
                                <div style="margin-top: 10px" v-for="(item,key) in works">
                                    <a v-if="typeof works[1] !==  'undefined'" style="margin-left: 20px;" href="#"
                                       @click="item.toggle=!item.toggle">@{{item.studName}}</a>
                                    <div v-if="item.toggle || typeof works[1] ===  'undefined'">
                                        <table style="margin-left: 40px"
                                               class="table table-sm" border="0">
                                            <tbody>
                                            <tr>
                                                <td width="200px">Имя руководителя</td>
                                                <td>
                                                    <span>@{{item.leaderName}}</span>
                                                </td>
                                                <td width="150px">
                                                    <button style="float: right;margin-right: 20px;" type="button"
                                                            class="btn btn-danger" @click="del(item)">Удалить работу
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="200px">Имя студента</td>
                                                <td>
                                                    <span>@{{item.studName}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="200px">Группа студента</td>
                                                <td>
                                                    <span>@{{item.gName}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="200px">Тема на английском</td>
                                                <td>
                                                    <span>@{{item.themeEn}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Тема на украинском</td>
                                                <td>
                                                    <span>@{{item.themeUkr}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Дата защиты</td>
                                                <td>
                                                    <span>@{{item.date}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Записка</td>
                                                <td>
                                                    <div v-show="item.file !== null && !item.editFile">
                                                        <a id="link" style="display: inline-block;"
                                                           :href="'/download/'+item.file"><i
                                                                    class="icon-download-alt"> </i>Скачать&nbsp;</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Количество страниц в записке</td>
                                                <td>
                                                    <span>@{{item.realPages}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Количество слайдов в презентации</td>
                                                <td>
                                                    <span>@{{item.graphicPages}}</span>
                                                </td>
                                            </tr>
                                            <tr v-if="item.r1n !== null">
                                                <td>Первый рецензент</td>
                                                <td>
                                                    <span><strong>Имя: </strong>@{{ item.r1n }}</span>
                                                    <p></p>
                                                    <span><strong>Место работы: </strong>@{{ item.r1w }}</span>
                                                    <p></p>
                                                    <span><strong>Должность: </strong> @{{ item.r1p }}</span>
                                                    <p></p>
                                                    <span><strong>Научная степень: </strong> @{{ item.r1d }}</span>
                                                </td>
                                                <td>
                                                    <div style="display: inline-block;margin-right: 20px; float: right;"
                                                         @click="delRev1(item)"
                                                         class="btn-sm btn-danger"><i
                                                                class="fa fa-times" aria-hidden="true"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr v-if="item.r2n !== null">
                                                <td>Второй рецензент</td>
                                                <td>
                                                    <span><strong>Имя: </strong>@{{ item.r2n }}</span>
                                                    <p></p>
                                                    <span><strong>Место работы: </strong>@{{ item.r2w }}</span>
                                                    <p></p>
                                                    <span><strong>Должность: </strong> @{{ item.r2p }}</span>
                                                    <p></p>
                                                    <span><strong>Научная степень: </strong> @{{ item.r2d }}</span>
                                                </td>
                                                <td>
                                                    <div style="display: inline-block;margin-right: 20px; float: right;"
                                                         @click="delRev2(item)"
                                                         class="btn-sm btn-danger"><i
                                                                class="fa fa-times" aria-hidden="true"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr v-if="item.questions[0]">
                                                <td>Вопросы</td>
                                                <td colspan="2">
                                                    <table class="table table-sm" border="0">
                                                        <tr>
                                                            <th>Экзаменатор</th>
                                                            <th>Вопрос</th>
                                                            <th>Оценка</th>
                                                        </tr>
                                                        <tr v-for="ques in item.questions">
                                                            <td><strong>@{{ ques.name}}</strong></td>
                                                            <td>@{{ ques.question }}</td>
                                                            <td><h3>@{{ ques.examinerRate }}</h3></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Рекомендация в аспирантуру</td>
                                                <td>
                                                    <label class="radio-inline"><input type="radio" v-model="item.pRec"
                                                                                       value="true"
                                                                                       @change="editRec(item)">&nbsp;<i
                                                                class="fa fa-check-circle"
                                                                style="color: #38c172"
                                                                aria-hidden="true"></i></label>
                                                    <label style="margin-left: 30px" class="radio-inline"><input
                                                                type="radio"
                                                                v-model="item.pRec"
                                                                value="false" @change="editRec(item)">&nbsp;<i
                                                                class="fa fa-times-circle" style="color: #e3342f;"
                                                                aria-hidden="true"></i></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Протокол</td>
                                                <td>
                                                    <h3>@{{item.prot}}</h3>
                                                    <div v-show="item.editProt" class="input-group mb-3">
                                                        <input v-model="item.prot" type="text"
                                                               class="form-control" aria-label="Name"
                                                               aria-describedby="button-addon2">
                                                        <div class="input-group-append">
                                                            <button @click="editProt(item)"
                                                                    class="btn btn-outline-secondary"
                                                                    type="button"
                                                                    id="button-addon2">ОК
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div style="display: inline-block;margin-right: 20px; float: right;">
                                                        <div v-show="!item.editProt" @click="item.editProt=true"
                                                             class="btn-sm btn-primary"><i
                                                                    class="fa fa-pencil" aria-hidden="false"></i>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Оценка</td>
                                                <td>
                                                    <h3>@{{item.rate}}</h3>
                                                    <div v-show="item.editTotal" class="input-group mb-3">
                                                        <input v-model="item.newTotalRate" type="number"
                                                               class="form-control" aria-label="Name" min="1" max="100"
                                                               aria-describedby="button-addon2">
                                                        <div class="input-group-append">
                                                            <button @click="editRate(item)"
                                                                    class="btn btn-outline-secondary"
                                                                    type="button"
                                                                    id="button-addon2">ОК
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div style="display: inline-block;margin-right: 20px; float: right;">
                                                        <div v-show="!item.editTotal" @click="item.editTotal=true"
                                                             class="btn-sm btn-primary"><i
                                                                    class="fa fa-pencil" aria-hidden="false"></i>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr v-if="item.rate !== null">
                                                <td>Оценка в национальной шкале</td>
                                                <td>
                                                    <h3 v-if="item.rate<60">Незадовільно</h3>
                                                    <h3 v-if="item.rate>=60 && item.rate<75">Задовільно</h3>
                                                    <h3 v-if="item.rate>=75 && item.rate<90 ">Добре</h3>
                                                    <h3 v-if="item.rate>90">Відмінно</h3>
                                                </td>
                                            </tr>
                                            <tr v-if="item.rate !== null">
                                                <td>Оценка в европейской шкале</td>
                                                <td>
                                                    <h2 v-if="item.rate<60">F</h2>
                                                    <h2 v-if="item.rate>=60 && item.rate<75">E</h2>
                                                    <h2 v-if="item.rate>=75 && item.rate<90 ">C</h2>
                                                    <h2 v-if="item.rate>90 && item.rate<96 ">B</h2>
                                                    <h2 v-if="item.rate>96">A</h2>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>

                    <span v-if="item.addRev===false && item.addQuestion===false && (item.rev1===null || item.rev2===null)"
                          style="margin-top: 10px"
                          class="btn btn-outline-secondary" @click="item.addRev=true">
                        Добавить рецензента
                    </span></td>
                                                <td>
                                            <span v-if="item.addRev===false && item.addQuestion===false"
                                                  style="margin-left: 20px; margin-top: 10px"
                                                  v-if="item.addQuestion===false"
                                                  class="btn btn-outline-secondary" @click="item.addQuestion=true">
                        Добавить вопрос
                    </span></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div v-if="item.addRev===true">
                                            <span>Новый рецензист:</span>
                                            <input style="margin-top: 5px" v-model="item.newRN" type="text"
                                                   class="form-control"
                                                   aria-label="Name"
                                                   aria-describedby="button-addon2" placeholder="ФИО">
                                            <input v-model="item.newRW" type="text" class="form-control"
                                                   aria-label="Name"
                                                   aria-describedby="button-addon2" placeholder="Место работы">
                                            <input v-model="item.newRP" type="text" class="form-control"
                                                   aria-label="Name"
                                                   aria-describedby="button-addon2" placeholder="Должность">
                                            <input v-model="item.newRD" type="text" class="form-control"
                                                   aria-label="Name"
                                                   aria-describedby="button-addon2" placeholder="Научная степень">
                                            <button style="margin-top: 10px;" @click="addNewRev(item)"
                                                    class="btn-sm btn-primary">Добавить
                                            </button>
                                        </div>
                                        <div v-if="item.addQuestion===true">
                                            <select v-model="item.newExID" class="custom-select"
                                                    id="inputGroupSelect01">
                                                <option></option>
                                                <option v-for="item1 in avEx" :value="item1.exID">@{{
                                                    item1.exName }}
                                                </option>
                                            </select>
                                            <input v-model="item.newQuestion" type="text" class="form-control"
                                                   aria-label="Name"
                                                   aria-describedby="button-addon2" placeholder="Вопрос">
                                            <input type="number" v-model="item.newExRate" class="form-control"
                                                   name="quantity" min="1" max="100"
                                                   placeholder="Оценка, от 1 до 100">
                                            <button @click="createQues(item)"
                                                    style="margin-top: 10px; float: right; margin-right: 15px"
                                                    class="btn-sm btn-primary" type="button"
                                                    id="button-addon2">Добавить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="nots.length != 0">
                            <p style="margin-top: 10%" align="center">Оповещения</p>
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
                group: [],
                department: [],
                hideNote: false,
                newNote: '',
                avEx: [],
            },
            methods: {
                createQues: function (item) {
                    var _this = this;
                    if (!item.newExID) {
                        alert("Выберите руководителя");
                        item.addQuestion = false;
                        return null;
                    }
                    let data = {pid: item.pID, exID: item.newExID, ques: item.newQuestion, rate: item.newExRate};
                    this.$http.post('/api/createNewQues', data).then(function (response) {
                        item.questions = response.data.ques;
                        item.addQuestion = false;
                    });
                },
                delRev1: function (item) {
                    let data = {id: item.rev1};
                    this.$http.post('/api/leaderDelRev', data);
                    item.rev1 = null;
                    item.r1n = null;
                    item.r1w = null;
                    item.r1p = null;
                    item.r1d = null;
                },
                editRate: function (item) {
                    if (!item.newTotalRate) {
                        alert("Введите оценку");
                        item.editTotal = false;
                        return null;
                    }
                    let data = {id: item.pID, rate: item.newTotalRate};
                    this.$http.post('/api/editRate', data).then(function (response) {
                        item.rate = item.newTotalRate;
                        item.editTotal = false;
                    });
                },
                editProt: function (item) {
                    let data = {id: item.pID, prot: item.prot};
                    this.$http.post('/api/editProt', data).then(function (response) {
                        item.editProt = false;
                    });
                },
                editRec: function (item) {
                    let data = {id: item.pID, rec: item.pRec};
                    this.$http.post('/api/editRec', data).then(function (response) {

                    });
                },
                addNewRev: function (item) {
                    var _this = this;
                    if (item.newRD === '' || item.newRW === '' || item.newRP === '' || item.newRN === '') {
                        return alert("Заполните все поля");
                        ;
                    }
                    if (item.rev1 && item.rev2) {
                        return alert("Нет мест");
                        ;
                    }
                    let data = {
                        name: item.newRN,
                        wp: item.newRN,
                        d: item.newRD,
                        p: item.newRP,
                        studentID: item.studentID
                    };
                    _this.$http.post('/api/leaderAddNewRev', data).then(function (response) {
                        if (item.rev1) {
                            item.rev2 = response.data.id;
                            item.r2n = item.newRN;
                            item.r2d = item.newRD;
                            item.r2w = item.newRW;
                            item.r2p = item.newRP;
                        } else {
                            item.rev1 = response.data.id;
                            item.r1n = item.newRN;
                            item.r1d = item.newRD;
                            item.r1w = item.newRW;
                            item.r1p = item.newRP;
                        }
                        item.addRev = false;
                    });
                },
                delRev2: function (item) {
                    let data = {id: item.rev2}
                    this.$http.post('/api/leaderDelRev', data)
                    item.rev2 = null;
                    item.r2n = null;
                    item.r2w = null;
                    item.r2p = null;
                    item.r2d = null;
                },
                chooseYourDestiny: function () {
                    this.$http.get('/api/adminMakeWorks')
                        .then(function () {
                            alert("Готово!");
                        });
                },
                makeNewNote: function () {
                    if (this.newNote !== '') {
                        let data = {text: this.newNote}
                        this.$http.post('/api/makeAdminNote', data)
                            .then(function () {
                                alert("Готово!");
                            });
                    }
                },
                del: function (item) {
                    var _this = this;
                    let data = {
                        data: item.id, studName: item.studName, leaderName: item.leaderName,
                        studID: item.studID, leaderID: item.leaderID, pid: item.pID, rev1: item.rev1, rev2: item.rev2
                    };
                    this.$http.post('/api/deleteWork', data).then(function (response) {
                        _.forEach(this.works, function (item1, key) {
                            if (item1.id === item.id)
                                _this.$delete(_this.works, key)
                        });
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
                            _this.group = response.data.group;
                            _this.avEx = response.data.avEx;
                        });
                },
            },
        });
    </script>
@endpush
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
                                <span class="input-group-text" id="basic-addon1">За датою</span>
                            </div>
                            <input type="text" class="form-control" placeholder="Введіть дату yyyy-mm-dd"
                                   aria-label="Username"
                                   aria-describedby="basic-addon1" v-model="date">
                            <div class="input-group-append">
                                <button @click="getOnDate" class="btn btn-outline-secondary" type="button"
                                        id="button-addon2">Отримати
                                </button>
                            </div>
                        </div>
                        <p></p>
                        <div style="margin-left: 20px" class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">За користувачем</span>
                            </div>
                            <input type="text" class="form-control" placeholder="Введіть почтову адресу"
                                   aria-label="Username"
                                   aria-describedby="basic-addon1" v-model="name">
                            <div class="input-group-append">
                                <button @click="getOnName" class="btn btn-outline-secondary" type="button"
                                        id="button-addon2">Отримати
                                </button>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div style="margin-left: 30px" v-if="user.name&&!showWhat">
                            <p>ПІБ користувача: @{{ user.name }}</p>
                            <p>Почта: @{{ user.email }}</p>
                            <p>Університет: @{{ department.unName }}</p>
                            <p>Кафедра: @{{ department.depName }}</p>
                            <p v-if="group.name!=='Керівники'">Група: @{{ group.name }}</p>
                            <p v-if="group.status">Тип навчання: @{{ group.status }}</p>
                            <p v-if="user.post">Посада: @{{ user.post }}</p>
                            <div v-if="user.userTypeID === 2 || user.userTypeID === 5">
                                <div style="display: inline-block;">Навантаження:</div>
                                <div style="display: inline-block;" v-show="!editLoad">@{{ user.leaderLoad }}
                                    <div style="display: inline-block;">
                                        <div style="display: inline-block;margin-right: 20px; float: right;">
                                            <div v-show="!editLoad" @click="editLoad=true"
                                                 class="btn-sm btn-primary" s><i
                                                        class="fa fa-pencil" aria-hidden="false"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="display: inline-block;" v-if="editLoad" class="input-group mb-3">
                                    <div class="input-group mb-3">
                                        <input type="number" v-model="leaderLoad" class="form-control"
                                               placeholder="Нагрузка" aria-label="Recipient's username"
                                               aria-describedby="button-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button"  @click="editL()"
                                                    id="button-addon2">OK
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <p></p>
                            </div>
                            <p v-if="user.userTypeID === 1">Роль: Студент</p>
                            <p v-if="user.userTypeID === 2">Роль: Керівник</p>
                            <p v-if="user.userTypeID === 3">Роль: Член ЕК</p>
                            <p v-if="user.userTypeID === 4">Роль: Адмін</p>
                            <p v-if="user.userTypeID === 5">Роль: Керівник + Член ЕК</p>
                            <div v-if="requests.length != 0">
                                <p style="margin-top: 20px" align="center">Запити</p>
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th scope="col">ПІБ студента</th>
                                        <th scope="col">ПІБ керівника</th>
                                        <th scope="col">Пріорітет студента</th>
                                        <th scope="col">Пріорітет керівника</th>
                                        <th scope="col">Віза</th>
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

                            <div v-if="typeof works[0] !== 'undefined'">
                                <p style="margin-top: 20px" align="center">Роботи</p>
                                <div style="margin-top: 10px" v-for="(item,key) in works">
                                    <a v-if="typeof works[1] !==  'undefined'" style="margin-left: 20px;" href="#"
                                       @click="item.toggle=!item.toggle">@{{item.studName}}</a>
                                    <div v-if="item.toggle || typeof works[1] ===  'undefined'">
                                        <table style="margin-left: 40px"
                                               class="table table-sm" border="0">
                                            <tbody>
                                            <tr>
                                                <td width="200px">ПІБ керівника</td>
                                                <td>
                                                    <span>@{{item.leaderName}}</span>
                                                </td>
                                                <td width="150px">
                                                    <button style="float: right;margin-right: 20px;" type="button"
                                                            class="btn btn-danger" @click="del(item)">Видалити роботу
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="200px">ПІБ студента</td>
                                                <td>
                                                    <span>@{{item.studName}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="200px">Група студента</td>
                                                <td>
                                                    <span>@{{item.gName}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="200px">Тема англійською</td>
                                                <td>
                                                    <span>@{{item.themeEn}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Тема українською</td>
                                                <td>
                                                    <span>@{{item.themeUkr}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Дата захисту</td>
                                                <td>
                                                    <span v-show="!item.editDate">@{{item.date}}</span>
                                                    <div style="display: inline-block;" v-if="item.editDate"
                                                         class="input-group mb-3">
                                                        <select v-model="item.newDate" class="custom-select"
                                                                id="inputGroupSelect01">
                                                            <option></option>
                                                            <option v-for="(item1,key) in avDates" :value="item1">@{{
                                                                item1 }}
                                                            </option>
                                                        </select>

                                                        <button @click="editNewDate(item)" class="btn btn-primary"
                                                                type="button">
                                                            ОК
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div style="display: inline-block;margin-right: 20px; float: right;">
                                                        <div v-show="!item.editDate" @click="showAvDates(item)"
                                                             class="btn-sm btn-primary"><i
                                                                    class="fa fa-pencil" aria-hidden="false"></i>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Напрацювання</td>
                                                <td>
                                                    <div v-show="item.file !== null && !item.editFile">
                                                        <a id="link" style="display: inline-block;"
                                                           :href="'/download/'+item.file+'/name/'+item.studName"><i
                                                                    class="icon-download-alt"> </i>Завантажити&nbsp;</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Кількість сторінок</td>
                                                <td>
                                                    <span>@{{item.realPages}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Кількість слайдів</td>
                                                <td>
                                                    <span>@{{item.graphicPages}}</span>
                                                </td>
                                            </tr>
                                            <tr v-if="item.r1n !== null">
                                                <td>Перший рецензент</td>
                                                <td>
                                                    <span><strong>ПІБ: </strong>@{{ item.r1n }}</span>
                                                    <p></p>
                                                    <span><strong>Місце роботи: </strong>@{{ item.r1w }}</span>
                                                    <p></p>
                                                    <span><strong>Посада: </strong> @{{ item.r1p }}</span>
                                                    <p></p>
                                                    <span><strong>Науковий ступ: </strong> @{{ item.r1d }}</span>
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
                                                <td>Другий рецензент</td>
                                                <td>
                                                    <span><strong>ПІБ: </strong>@{{ item.r2n }}</span>
                                                    <p></p>
                                                    <span><strong>Місце роботи: </strong>@{{ item.r2w }}</span>
                                                    <p></p>
                                                    <span><strong>Посада: </strong> @{{ item.r2p }}</span>
                                                    <p></p>
                                                    <span><strong>Науковий ступ: </strong> @{{ item.r2d }}</span>
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
                                                <td>Оцінки членів ЕК</td>
                                                <td colspan="2">
                                                    <table class="table table-sm" border="0">
                                                        <tr>
                                                            <th>Екзаменатор</th>
                                                            <th>Питання</th>
                                                            <th>Оцінка</th>
                                                            <th>Видалити</th>
                                                        </tr>
                                                        <tr v-for="ques in item.questions">
                                                            <td><strong>@{{ ques.name}}</strong></td>
                                                            <td>@{{ ques.question }}</td>
                                                            <td><h3>@{{ ques.examinerRate }}</h3></td>
                                                            <td>
                                                                <div style="display: inline-block;"
                                                                     @click="delQues(item, ques)"
                                                                     class="btn-sm btn-danger"><i
                                                                            class="fa fa-times" aria-hidden="true"></i>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Рекомендація в аспірантуру</td>
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
                                                <td>Номер протоколу</td>
                                                <td>
                                                    <h3>@{{item.prot}}</h3>
                                                    <div v-show="item.editProt" class="input-group mb-3">
                                                        <input v-model="item.newProtocol" type="text"
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
                                                <td>Підсумковий бал</td>
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
                                                <td>У національній шкалі</td>
                                                <td>
                                                    <h3 v-if="item.rate<60">Незадовільно</h3>
                                                    <h3 v-if="item.rate>=60 && item.rate<75">Задовільно</h3>
                                                    <h3 v-if="item.rate>=75 && item.rate<90 ">Добре</h3>
                                                    <h3 v-if="item.rate>=90">Відмінно</h3>
                                                </td>
                                            </tr>
                                            <tr v-if="item.rate !== null">
                                                <td>У європейскій шкалі</td>
                                                <td>
                                                    <h2 v-if="item.rate<60">F</h2>
                                                    <h2 v-if="item.rate>=60 && item.rate<75">E</h2>
                                                    <h2 v-if="item.rate>=75 && item.rate<90 ">C</h2>
                                                    <h2 v-if="item.rate>=90 && item.rate<96 ">B</h2>
                                                    <h2 v-if="item.rate>=96">A</h2>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>

                    <span v-if="item.addRev===false && item.addQuestion===false && (item.rev1===null || item.rev2===null)"
                          style="margin-top: 10px"
                          class="btn btn-outline-secondary" @click="item.addRev=true">
                        Додати рецензента
                    </span></td>
                                                <td>
                                            <span v-if="item.addRev===false && item.addQuestion===false"
                                                  style="margin-left: 20px; margin-top: 10px"
                                                  v-if="item.addQuestion===false"
                                                  class="btn btn-outline-secondary" @click="item.addQuestion=true">
                        Додати єкзаменатора
                    </span></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div v-if="item.addRev===true">
                                            <span>Новий рецензент:</span>
                                            <input style="margin-top: 5px" v-model="item.newRN" type="text"
                                                   class="form-control"
                                                   aria-label="Name"
                                                   aria-describedby="button-addon2" placeholder="ПІБ">
                                            <input v-model="item.newRW" type="text" class="form-control"
                                                   aria-label="Name"
                                                   aria-describedby="button-addon2" placeholder="Місце роботи">
                                            <input v-model="item.newRP" type="text" class="form-control"
                                                   aria-label="Name"
                                                   aria-describedby="button-addon2" placeholder="Посада">
                                            <input v-model="item.newRD" type="text" class="form-control"
                                                   aria-label="Name"
                                                   aria-describedby="button-addon2" placeholder="Науковий ступ">
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
                                                   aria-describedby="button-addon2" placeholder="Питання">
                                            <input type="number" v-model="item.newExRate" class="form-control"
                                                   name="quantity" min="1" max="100"
                                                   placeholder="Оцінка, від 1 до 100">
                                            <button @click="createQues(item)"
                                                    style="margin-top: 10px; float: right; margin-right: 15px"
                                                    class="btn-sm btn-primary" type="button"
                                                    id="button-addon2">Додати
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="nots.length != 0">
                            <p style="margin-top: 10%" align="center">Сповіщення</p>
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
                <div @click="hideNote=!hideNote" class="btn btn-primary">Нове сповіщення</div>
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
                <div @click="chooseYourDestiny" class="btn btn-primary">Опрацювати всі запити</div>
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
                avDates: [],
                works: [],
                group: [],
                department: [],
                hideNote: false,
                editLoad: false,
                leaderLoad: 0,
                newNote: '',
                avEx: [],
            },
            methods: {
                editL: function () {
                    if (this.leaderLoad < 1 || this.leaderLoad > 15) {
                        alert("Навантаження повинно бути від 1 до 15");
                        this.editLoad = false;
                        return null;
                    }
                    let data = {id: this.user.id, leaderLoad: this.leaderLoad};
                    this.$http.post('/api/editLeaderLoad', data).then(function (response) {
                        this.user.leaderLoad = this.leaderLoad;
                        this.editLoad = false;
                    });
                },
                createQues: function (item) {
                    if (!item.newExID) {
                        alert("Оберіть керівника");
                        return null;
                    }
                    if (item.newExRate < 1 || item.newExRate > 100) {
                        alert("Оцінка повинна бути від 1 до 100");
                        item.editTotal = false;
                        return null;
                    }
                    let data = {pid: item.pID, exID: item.newExID, ques: item.newQuestion, rate: item.newExRate};
                    this.$http.post('/api/createNewQues', data).then(function (response) {
                        item.questions = response.data.ques;
                        item.newExID = null;
                        item.newQuestion = null;
                        item.newExRate = null;
                        item.addQuestion = false;
                    });
                },
                editNewDate: function (item) {
                    var _this = this;
                    item.editDate = false;
                    if (item.newDate !== item.date) {
                        let data = {data: item}
                        this.$http.post('/api/adminChangeDate', data)
                            .then(function () {
                                item.date = item.newDate;
                            });
                    }
                },
                showAvDates: function (item) {
                    var _this = this;
                    item.editDate = true;
                    this.$http.get('/api/studGetAvDates')
                        .then(function (response) {
                            _this.avDates = response.data.data;
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
                        alert("Введіть оцінку");
                        item.editTotal = false;
                        return null;
                    }
                    if (item.newTotalRate < 1 || item.newTotalRate > 100) {
                        alert("Оцінка повинна бути від 1 до 100");
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
                    let data = {id: item.pID, prot: item.newProtocol};
                    this.$http.post('/api/editProt', data).then(function (response) {
                        item.prot = item.newProtocol;
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
                        return alert("Заполніть усі поля");
                        ;
                    }
                    if (item.rev1 && item.rev2) {
                        return alert("Нему місць");
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
                delQues: function (item, ques) {
                    var _this = this;
                    let data = {id: ques.id};
                    this.$http.post('/api/adminDelQues', data)
                        .then(function () {
                            _.forEach(_this.works, function (i, key) {
                                if (i.id === item.id) {
                                    _.forEach(_this.works[key].questions, function (i2, key2) {
                                        if (i2.id === ques.id) {
                                            _this.works[key].questions.splice(key2, 1);
                                            return null;
                                        }
                                    });
                                }
                            });
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
                        data: item.id, studName: item.studName,
                        studID: item.studID, leaderID: item.leaderID, pid: item.pID, rev1: item.rev1, rev2: item.rev2
                    };
                    this.$http.post('/api/deleteWork', data).then(function (response) {
                        _.forEach(_this.works, function (i, key) {
                            if (i.id === item.id)
                                _this.works.splice(key, 1);
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
                    this.leaderLoad = 0;
                    this.editLoad=false;
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
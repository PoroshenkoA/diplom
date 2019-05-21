@extends('layouts.app')

@section('content')

    <div class="container" id="leaderHome">
        <div class="row justify-content-center">
            <div v-if="type.userTypeID===5" class="btn-group btn-group-lg" role="group" aria-label="ex">
                <h1>Я:&nbsp;</h1>
                <button type="button" @click="likeNormal=true" class="btn btn-secondary">Керівник</button>
                <button type="button" @click="getLikeExaminer" class="btn btn-secondary">Член ЕК</button>
            </div>
        </div>
        <div v-if="type.userTypeID===2 || likeNormal" style="margin-top: 20px">
            <div v-if="students.length != 0">
                <div>
                    <p align="center">Мої запити</p>
                    <table v-if="students.length != 0" class="table table-sm">
                        <thead>
                        <tr>
                            <th scope="col">ПІБ</th>
                            <th scope="col">Група</th>
                            <th scope="col">Пріорітет</th>
                            <th scope="col">Віза</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item,key) in students">
                            <td>
                                <div style="display: inline-block;">@{{item.name}}</div>
                            </td>
                            <td>
                                <div style="display: inline-block;">@{{item.groupName}}</div>
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
                <div @click="send" class="btn btn-primary">Зберігти</div>
            </div>
            <div style="margin-left: 10%; margin-top: 20px;" v-if="students.length == 0 && works.length == 0">
                Запитів від студентів немає.
            </div>
            <div v-if="works.length != 0">
                <p style="margin-top: 20px" align="center">Роботи</p>
                <div style="margin-top: 20px" v-for="(item,key) in works">
                    <a style="margin-left: 20px" href="#" @click="item.toggle=!item.toggle">@{{item.studName}}</a>
                    <div style="margin-left: 40px" v-if="item.toggle===true">
                        <table class="table table-sm" border="0">
                            <tbody>
                            <tr>
                                <td width="200px">Тема ангійською</td>
                                <td>
                                    <span v-show="!item.editThemeEn">@{{item.themeEn}}</span>
                                    <div v-show="item.editThemeEn" class="input-group mb-3">
                                        <input v-model="item.newThemeEn" type="text" class="form-control"
                                               aria-label="Name"
                                               aria-describedby="button-addon2">
                                        <div class="input-group-append">
                                            <button @click="editEn(item)" class="btn btn-outline-secondary"
                                                    type="button"
                                                    id="button-addon2">ОК
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td width="10%">
                                    <div style="display: inline-block;margin-right: 20px; float: right;">
                                        <div v-show="!item.editThemeEn" @click="item.editThemeEn=true"
                                             class="btn-sm btn-primary"><i
                                                    class="fa fa-pencil" aria-hidden="false"></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Тема українською</td>
                                <td>
                                    <span v-show="!item.editThemeUkr">@{{item.themeUkr}}</span>
                                    <div v-show="item.editThemeUkr" class="input-group mb-3">
                                        <input v-model="item.newThemeUkr" type="text" class="form-control"
                                               aria-label="Name"
                                               aria-describedby="button-addon2">
                                        <div class="input-group-append">
                                            <button @click="editUkr(item)" class="btn btn-outline-secondary"
                                                    type="button"
                                                    id="button-addon2">ОК
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: inline-block;margin-right: 20px; float: right;">
                                        <div v-show="!item.editThemeUkr" @click="item.editThemeUkr=true"
                                             class="btn-sm btn-primary"><i
                                                    class="fa fa-pencil" aria-hidden="false"></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Дата захисту</td>
                                <td>
                                    <span>@{{item.date}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Напрацювання</td>
                                <td>
                                    <div v-show="item.file !== null && !item.editFile">
                                        <a id="link" style="display: inline-block;"
                                           :href="'/download/'+item.file+'/name/'+item.studName"><i
                                                    class="icon-download-alt"> </i>Завантажити&nbsp</a>
                                    </div>
                                    <div v-show="item.editFile" class="input-group">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <input type="file" style="display: none" :id="'file'+key"
                                                   @change="onFileChange">
                                            <label :for="'file'+key" class="btn btn-secondary">Обрати</label>
                                            <label type="button" @click="sendFile(item)"
                                                   class="btn btn-secondary">ОК</label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: inline-block;margin-right: 20px; float: right;">
                                        <div v-show="!item.editFile" @click="item.editFile=true"
                                             class="btn-sm btn-primary"><i
                                                    class="fa fa-pencil" aria-hidden="false"></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Кількість сторінок</td>
                                <td>
                                    <span v-show="!item.editRealPages">@{{item.realPages}}</span>
                                    <div v-show="item.editRealPages" class="input-group mb-3">
                                        <input v-model="item.realPages" type="text" class="form-control"
                                               aria-label="Name"
                                               aria-describedby="button-addon2">
                                        <div class="input-group-append">
                                            <button @click="editRP(item)" class="btn btn-outline-secondary"
                                                    type="button"
                                                    id="button-addon2">ОК
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: inline-block;margin-right: 20px; float: right;">
                                        <div v-show="!item.editRealPages" @click="item.editRealPages=true"
                                             class="btn-sm btn-primary"><i
                                                    class="fa fa-pencil" aria-hidden="false"></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Кількість слайдів</td>
                                <td>
                                    <span v-show="!item.editPresentationPages">@{{item.graphicPages}}</span>
                                    <div v-show="item.editPresentationPages" class="input-group mb-3">
                                        <input v-model="item.graphicPages" type="text" class="form-control"
                                               aria-label="Name"
                                               aria-describedby="button-addon2">
                                        <div class="input-group-append">
                                            <button @click="editGP(item)" class="btn btn-outline-secondary"
                                                    type="button"
                                                    id="button-addon2">ОК
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: inline-block;margin-right: 20px; float: right;">
                                        <div v-show="!item.editPresentationPages"
                                             @click="item.editPresentationPages=true"
                                             class="btn-sm btn-primary"><i
                                                    class="fa fa-pencil" aria-hidden="false"></i>
                                        </div>
                                    </div>
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
                                            <th>Укзаменатор</th>
                                            <th>Питання</th>
                                            <th>Оцінка</th>
                                        </tr>
                                        <tr v-for="ques in item.questions">
                                            <td><strong>@{{ ques.name}}</strong></td>
                                            <td>@{{ ques.question }}</td>
                                            <td><h3>@{{ ques.examinerRate }}</h3></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr v-if="item.prot !== null">
                                <td>Номер протоколу</td>
                                <td>
                                    <h3 style="margin-left: 30%">@{{item.prot}}</h3>
                                </td>
                            </tr>
                            <tr v-if="item.rate !== null">
                                <td>Ітоговий бал</td>
                                <td>
                                    <h3 style="margin-left: 30%">@{{item.rate}}</h3>
                                </td>
                            </tr>
                            <tr v-if="item.rate !== null">
                                <td>У національній шкалі</td>
                                <td>
                                    <h3 style="margin-left: 30%" v-if="item.rate<60">Незадовільно</h3>
                                    <h3 style="margin-left: 30%" v-if="item.rate>=60 && item.rate<75">Задовільно</h3>
                                    <h3 style="margin-left: 30%" v-if="item.rate>=75 && item.rate<90 ">Добре</h3>
                                    <h3 style="margin-left: 30%" v-if="item.rate>=90">Відмінно</h3>
                                </td>
                            </tr>
                            <tr v-if="item.rate !== null">
                                <td>У європейскій шкалі</td>
                                <td>
                                    <h3 style="margin-left: 30%" v-if="item.rate<60">F</h3>
                                    <h3 style="margin-left: 30%" v-if="item.rate>=60 && item.rate<75">E</h3>
                                    <h3 style="margin-left: 30%" v-if="item.rate>=75 && item.rate<90 ">C</h3>
                                    <h3 style="margin-left: 30%" v-if="item.rate>=90 && item.rate<96 ">B</h3>
                                    <h3 style="margin-left: 30%" v-if="item.rate>=96">A</h3>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div>
                    <span v-if="item.addRev===false && (item.rev1===null || item.rev2===null)"
                          class="btn btn-outline-secondary" @click="item.addRev=true">
                        Додати рецензента
                    </span>
                            <div v-if="item.addRev===true">
                                <input v-model="item.newRN" type="text" class="form-control" aria-label="Name"
                                       aria-describedby="button-addon2" placeholder="ПІБ">
                                <input v-model="item.newRW" type="text" class="form-control" aria-label="Name"
                                       aria-describedby="button-addon2" placeholder="Місце роботи">
                                <input v-model="item.newRP" type="text" class="form-control" aria-label="Name"
                                       aria-describedby="button-addon2" placeholder="Посада">
                                <input v-model="item.newRD" type="text" class="form-control" aria-label="Name"
                                       aria-describedby="button-addon2" placeholder="Науковий ступ">
                                <button style="margin-top: 10px;" @click="addNewRev(item)"
                                        class="btn-sm btn-primary">Додати
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="likeNormal===false">
            <div v-if="works.length != 0">
                <p style="margin-top: 20px" align="center">Роботи</p>
                <div style="margin-bottom: 20px" v-for="(item,key) in worksEx">
                    <a style="margin-left: 20px" href="#" @click="item.toggle=!item.toggle">@{{item.groupName+" "+item.studName}}</a>
                    <div style="display: inline-block; float:right; margin-right: 10px">
                        <div style="display: inline-block;margin-left: 10px;" v-show="!item.edit"
                             @click="item.edit=!item.edit"
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
                    <div style="margin-left: 50px; margin-top: 15px" v-if="item.toggle===true">
                        <table class="table table-sm" border="0">
                            <tbody>
                            <tr>
                                <td width="200px">ПІБ керівника</td>
                                <td>
                                    <span>@{{item.leaderName}}</span>
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
                                    <span>@{{item.date}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Напрацювання</td>
                                <td>
                                    <div v-show="item.file !== null && !item.editFile">
                                        <a id="link" style="display: inline-block;" :href="'/download/'+item.file+'/name/'+item.studName"><i
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
                            </tr>
                            <tr v-if="item.questions[0]">
                                <td>Оцінки членів ЕК</td>
                                <td colspan="2">
                                    <table class="table table-sm" border="0">
                                        <tr>
                                            <th>Екзаменатор</th>
                                            <th>Пітання</th>
                                            <th>Оцінка</th>
                                        </tr>
                                        <tr v-for="ques in item.questions">
                                            <td><strong>@{{ ques.name}}</strong></td>
                                            <td>@{{ ques.question }}</td>
                                            <td><h3>@{{ ques.examinerRate }}</h3></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr v-if="item.prot !== null">
                                <td>Номер протоколу</td>
                                <td>
                                    <h3 style="margin-left: 30%">@{{item.prot}}</h3>
                                </td>
                            </tr>
                            <tr v-if="item.rate !== null">
                                <td>Ітоговий бал</td>
                                <td>
                                    <h3 style="margin-left: 30%">@{{item.rate}}</h3>
                                </td>
                            </tr>
                            <tr v-if="item.rate !== null">
                                <td>У національній шкалі</td>
                                <td>
                                    <h3 style="margin-left: 30%" v-if="item.rate<60">Незадовільно</h3>
                                    <h3 style="margin-left: 30%" v-if="item.rate>=60 && item.rate<75">Задовільно</h3>
                                    <h3 style="margin-left: 30%" v-if="item.rate>=75 && item.rate<90 ">Добре</h3>
                                    <h3 style="margin-left: 30%" v-if="item.rate>=90">Відмінно</h3>
                                </td>
                            </tr>
                            <tr v-if="item.rate !== null">
                                <td>У європейскій шкалі</td>
                                <td>
                                    <h3 style="margin-left: 30%" v-if="item.rate<60">F</h3>
                                    <h3 style="margin-left: 30%" v-if="item.rate>=60 && item.rate<75">E</h3>
                                    <h3 style="margin-left: 30%" v-if="item.rate>=75 && item.rate<90 ">C</h3>
                                    <h3 style="margin-left: 30%" v-if="item.rate>=90 && item.rate<96 ">B</h3>
                                    <h3 style="margin-left: 30%" v-if="item.rate>=96">A</h3>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div v-else>
                <span>Робіт пока що немає.</span>
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
                        <div style="display: inline-block; float:right; width: 90px" @click="hideAllNotes()"
                             class="btn-sm btn-danger">Видалити всі
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
        var vLeader = new Vue({
            el: '#leaderHome',
            data: {
                text: [],
                students: [],
                works: [],
                worksEx: [],
                type: "2",
                likeNormal: null,
                file: null,
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
                    } else alert("Неможливо видалити сповіщення адміністратора");
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
                addNewRev: function (item) {
                    var _this = this;
                    if (item.newRD === '' || item.newRW === '' || item.newRP === '' || item.newRN === '') {
                        return alert("Заповніть усі поля");
                        ;
                    }
                    if (item.rev1 && item.rev2) {
                        return alert("Нема місць");
                        ;
                    }
                    let data = {
                        name: item.newRN,
                        wp: item.newRW,
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
                            item.newRN = '';
                            item.newRD = '';
                            item.newRW = '';
                            item.newRP = '';
                        } else {
                            item.rev1 = response.data.id;
                            item.r1n = item.newRN;
                            item.r1d = item.newRD;
                            item.r1w = item.newRW;
                            item.r1p = item.newRP;
                            item.newRN = '';
                            item.newRD = '';
                            item.newRW = '';
                            item.newRP = '';
                        }
                        item.addRev = false;
                    });
                },
                editRP: function (item) {
                    let data = {data: item}
                    this.$http.post('/api/leaderEditRealPages', data)
                    item.editRealPages = false;
                },
                delRev1: function (item) {
                    let data = {id: item.rev1}
                    this.$http.post('/api/leaderDelRev', data)
                    item.rev1 = null;
                    item.r1n = null;
                    item.r1w = null;
                    item.r1p = null;
                    item.r1d = null;
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
                editGP: function (item) {
                    let data = {data: item}
                    this.$http.post('/api/leaderEditGPages', data)
                    item.editPresentationPages = false;
                },
                editEn: function (item) {
                    if (item.newThemeEn !== "" && item.newThemeEn !== item.themeEn) {
                        let data = {data: item}
                        this.$http.post('/api/leaderChangeThemeEn', data)
                            .then(function () {
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
                                item.themeUkr = item.newThemeUkr;
                            });
                    }
                    item.editThemeUkr = false;
                },
                onFileChange(e) {
                    var files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                    this.file = files[0];
                },
                sendFile: function (item) {
                    if (item.newFile !== null) {
                        var _this = this;
                        var formData = new FormData();
                        formData.append('file', _this.file);
                        formData.append('uuid', item.file);
                        formData.append('studentID', item.studentID);
                        _this.$http.post('/api/leaderSendFile', formData)
                            .then(function (response) {
                                item.editFile = false;
                                item.file = response.data.docName;
                            }).catch(function (respond) {
                            item.editFile = false;
                            alert("Необхідний формат файлу - .docx")
                        });
                    }
                    item.editFile = false;
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
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class UpdateUsers extends Command
{
    protected $users = 
    [
        [
          "name" => "Eddy Mulyadi,  Dr., Ir",
          "nip" => "195609231986031001"
        ],
        [
          "name" => "Isya Nurrahmat Dana, Dr. Ir.",
          "nip" => "195311141984031001"
        ],
        [
          "name" => "Enar Kusdinar  Abdurachman, Dr., Ir., DEA.",
          "nip" => "195402071986031001"
        ],
        [
          "name" => "Kasbani, Ir., M.Sc.",
          "nip" => "196110301991031001"
        ],
        [
          "name" => "Eka Kadarsetia, Ir., M.Sc.",
          "nip" => "196101221987031001"
        ],
        [
          "name" => "Herry Purnomo, Ir., M.T.",
          "nip" => "195809071989031001"
        ],
        [
          "name" => "Cecep Sulaeman, Drs.",
          "nip" => "195904061989031001"
        ],
        [
          "name" => "N. Euis Sutaningsih, Ir., M.Tech.",
          "nip" => "196112101991032001"
        ],
        [
          "name" => "Iing Kusnadi  Ir.",
          "nip" => "196203131991031002"
        ],
        [
          "name" => "Dewi Sri Sayudi,  Ir.",
          "nip" => "196307271990032001"
        ],
        [
          "name" => "Gede Suantika, Ir., M.Si.",
          "nip" => "196112051991031001"
        ],
        [
          "name" => "Sri Hidayati, Dr. Ir.",
          "nip" => "196708121994032002"
        ],
        [
          "name" => "Hanik Humaida, Dr. Dra., M.Sc.",
          "nip" => "196505231991032002"
        ],
        [
          "name" => "Yudhicara, S.T., M.Si.",
          "nip" => "197009041997032001"
        ],
        [
          "name" => "Anas Luthfi, Ir., M.T.",
          "nip" => "195808081990031001"
        ],
        [
          "name" => "M.Ch. Supriyati Dwi Andreastuti, Dr., Ir.",
          "nip" => "196209171991032001"
        ],
        [
          "name" => "Subandriyo, Drs, M.Si.",
          "nip" => "196206121990031001"
        ],
        [
          "name" => "Mochamad Nugraha Kartadinata, Dr. Ir.,",
          "nip" => "196610081994031001"
        ],
        [
          "name" => "I Gusti Made Agung Nandaka, Ir., DEA.",
          "nip" => "196412271993031005"
        ],
        [
          "name" => "Imam Santosa, Ir., M.Sc.",
          "nip" => "196308231993031001"
        ],
        [
          "name" => "Agus Sampurno, Ir.",
          "nip" => "196708101993031001"
        ],
        [
          "name" => "Isa Nurnusanto,  Ir.",
          "nip" => "195908031994031001"
        ],
        [
          "name" => "Cahya Patria, Ir., M.T.",
          "nip" => "196307091994031002"
        ],
        [
          "name" => "Heru Pamungkas, Ir.",
          "nip" => "195809261991031002"
        ],
        [
          "name" => "Dadang Yusuf, S. Sos",
          "nip" => "196007151981031003"
        ],
        [
          "name" => "Wawan Irawan, Ir.",
          "nip" => "196203281992031001"
        ],
        [
          "name" => "Kristianto, Ir, M.Si.",
          "nip" => "196612191996031001"
        ],
        [
          "name" => "Estu Kriswati, Dr. S.T., M.Sc.",
          "nip" => "196905171997032001"
        ],
        [
          "name" => "Supartoyo, Dr. S.T., M.T.",
          "nip" => "196809221995031001"
        ],
        [
          "name" => "Umar Rosadi, S.T.",
          "nip" => "196309162002121002"
        ],
        [
          "name" => "Hetty Triastuty, Dr. S.Si, M.Sc.",
          "nip" => "197106231998032001"
        ],
        [
          "name" => "Anjar Heriwaseso, S.T., M.T.",
          "nip" => "197704302006041002"
        ],
        [
          "name" => "Pretina Sitinjak, S.T.",
          "nip" => "196511071994032001"
        ],
        [
          "name" => "Sri Sumarti, Dra.",
          "nip" => "196107061990032003"
        ],
        [
          "name" => "Padmadi Heru Wibawa, Drs., M.Si.",
          "nip" => "196211251994031002"
        ],
        [
          "name" => "Nia Kurnia Praja, Ir, M.T.",
          "nip" => "196404301994031001"
        ],
        [
          "name" => "Anugrah Prasetya,  Ir. M.Sc.",
          "nip" => "196309111991031001"
        ],
        [
          "name" => "Mamay Surmayadi, S.T., M.Sc.",
          "nip" => "197003261998031001"
        ],
        [
          "name" => "Agus Budianto, Ir.",
          "nip" => "196508231994031001"
        ],
        [
          "name" => "Nurudin, S.Si.",
          "nip" => "196805011991031004"
        ],
        [
          "name" => "Enny Ermiyati, S.E.",
          "nip" => "197004261993032008"
        ],
        [
          "name" => "Sofie Yusmira Oktane W., Rd, S.H., M.H.",
          "nip" => "198110212006042002"
        ],
        [
          "name" => "Agus Budi Santoso, Dr., S.Si., M.Sc.",
          "nip" => "198008272005021001"
        ],
        [
          "name" => "Kusdaryanto, Ir., M.T.",
          "nip" => "196304291996031001"
        ],
        [
          "name" => "Akhmad Solikhin, Dr., S.Si, DEA.",
          "nip" => "198006212005021014"
        ],
        [
          "name" => "Muhammad Arifin Joko Pradipto, S.T., M.E.",
          "nip" => "196808152002121001"
        ],
        [
          "name" => "Sumaryono, Dr. S.T., M.Eng.",
          "nip" => "197307232006041001"
        ],
        [
          "name" => "Agus Solihin, Ir.",
          "nip" => "196208221990031001"
        ],
        [
          "name" => "Devy Kamil Syahbana, Dr., S.Si.",
          "nip" => "198102182006041001"
        ],
        [
          "name" => "Rita Haini, S.IP.",
          "nip" => "196501011987032001"
        ],
        [
          "name" => "Karyanti Perdhani Putri",
          "nip" => "196405201984032001"
        ],
        [
          "name" => "Sujono",
          "nip" => "196103241987031001"
        ],
        [
          "name" => "Sukamti",
          "nip" => "196007151987032001"
        ],
        [
          "name" => "Halia",
          "nip" => "196706051988032002"
        ],
        [
          "name" => "Nia Haerani, Dr. S.T., M.T.",
          "nip" => "197108302003122001"
        ],
        [
          "name" => "Sunarta",
          "nip" => "196105041983031006"
        ],
        [
          "name" => "Heri Supartono",
          "nip" => "196012141983031002"
        ],
        [
          "name" => "Budiman  Kurniawan, A.P.",
          "nip" => "196204071984031002"
        ],
        [
          "name" => "Bambang  Heri Purwanto",
          "nip" => "196206041984031002"
        ],
        [
          "name" => "Yulianto",
          "nip" => "197207171993031002"
        ],
        [
          "name" => "Endi T Bina",
          "nip" => "196312091984031001"
        ],
        [
          "name" => "Farid Ruskanda Bina, A.Md.",
          "nip" => "197201051992031004"
        ],
        [
          "name" => "Anak Agung Anom Karsana",
          "nip" => "196212011989031001"
        ],
        [
          "name" => "Heru Suparwaka",
          "nip" => "196406191993031001"
        ],
        [
          "name" => "Momon, A.P.",
          "nip" => "196402151988031001"
        ],
        [
          "name" => "Krisno Wahyongko",
          "nip" => "196412101988031002"
        ],
        [
          "name" => "Ade Koswara",
          "nip" => "197004041992031002"
        ],
        [
          "name" => "Sudrajat",
          "nip" => "196205261989031001"
        ],
        [
          "name" => "Darno Lamane",
          "nip" => "196304281989031001"
        ],
        [
          "name" => "Patris Sasombo",
          "nip" => "196509091986031002"
        ],
        [
          "name" => "Liswanto, A.P.",
          "nip" => "197011011992031003"
        ],
        [
          "name" => "Sukedi",
          "nip" => "196504031986031003"
        ],
        [
          "name" => "Junaidin",
          "nip" => "196212311986031010"
        ],
        [
          "name" => "Bambang Santoso",
          "nip" => "196309131984031001"
        ],
        [
          "name" => "Sofyan Primulyana, S.T., M.T.",
          "nip" => "196807071992031018"
        ],
        [
          "name" => "Nurnaning Aisyah, S.Si.",
          "nip" => "197802232006042001"
        ],
        [
          "name" => "Ugan Boyson Saing, S.Si.",
          "nip" => "197107232005021002"
        ],
        [
          "name" => "Agoes Loeqman, S.Si",
          "nip" => "197308132006041001"
        ],
        [
          "name" => "Asep Nursalim, S.T., M.T.",
          "nip" => "197407112006041002"
        ],
        [
          "name" => "Oktory Prambada, S.T., M.Sc.",
          "nip" => "198010202006041002"
        ],
        [
          "name" => "Yunara Dasa Triana, S.T., M.T.",
          "nip" => "197211102006041001"
        ],
        [
          "name" => "Novie Noor Afatia, S.T., M.T.",
          "nip" => "197912182006042002"
        ],
        [
          "name" => "Riyadi",
          "nip" => "196203091988031001"
        ],
        [
          "name" => "Nana  Rukmana",
          "nip" => "196008151987031003"
        ],
        [
          "name" => "Deden Junaedi",
          "nip" => "196311231986031001"
        ],
        [
          "name" => "Gangsar Turjono",
          "nip" => "196705271990031001"
        ],
        [
          "name" => "Suharna",
          "nip" => "196102071988031001"
        ],
        [
          "name" => "Miswanta",
          "nip" => "196205161988031002"
        ],
        [
          "name" => "Toto Rukada",
          "nip" => "196112171989031002"
        ],
        [
          "name" => "Aep Dahlan",
          "nip" => "196206041991031001"
        ],
        [
          "name" => "Yulyati, SAP. MAP.",
          "nip" => "196801171990032001"
        ],
        [
          "name" => "Mardian Hardipto, S.T., M.T.",
          "nip" => "197507042002121001"
        ],
        [
          "name" => "Cipta Muhamad Firmansyah, S.T., M.T.",
          "nip" => "198211302006041001"
        ],
        [
          "name" => "Sumartiani, S.H.",
          "nip" => "196812211993032007"
        ],
        [
          "name" => "Tri Nia Kurniasih, S.E.",
          "nip" => "197511122005022001"
        ],
        [
          "name" => "Athanasius Cipta, S.T., MDM.",
          "nip" => "197308122006041001"
        ],
        [
          "name" => "Efrita Lusy Andriany Saragih, S.T.",
          "nip" => "198006152006042002"
        ],
        [
          "name" => "Yoga Era Pamitro, S.T., M.Si.",
          "nip" => "198103052006041003"
        ],
        [
          "name" => "Novi Handono, S.H.",
          "nip" => "197211032005021001"
        ],
        [
          "name" => "Yukni Arifianti, S.T., M.T.",
          "nip" => "197810252006042001"
        ],
        [
          "name" => "Ahmad Basuki, S.Si, M.Si.",
          "nip" => "197707152006041001"
        ],
        [
          "name" => "Bernadinus Dinu Tobi",
          "nip" => "196505151989031002"
        ],
        [
          "name" => "Triyono",
          "nip" => "196602131989031001"
        ],
        [
          "name" => "Kasimirus  Bele Muda",
          "nip" => "196503041989031001"
        ],
        [
          "name" => "I Nyoman Gina Wijaya",
          "nip" => "197303071993031001"
        ],
        [
          "name" => "Budi Prianto",
          "nip" => "197012011993031001"
        ],
        [
          "name" => "Sigit  Widianto",
          "nip" => "196304231990031001"
        ],
        [
          "name" => "Susanto",
          "nip" => "196803231991031002"
        ],
        [
          "name" => "Khoirul Huda",
          "nip" => "196603171990031001"
        ],
        [
          "name" => "Moh. Syafi'i",
          "nip" => "196012041988031001"
        ],
        [
          "name" => "Rusdi Nurdin",
          "nip" => "196405151989031001"
        ],
        [
          "name" => "Rokhman",
          "nip" => "196202271990031001"
        ],
        [
          "name" => "Agustinus Tae",
          "nip" => "196107181989031001"
        ],
        [
          "name" => "Bertolomeus Hirpaji Roma",
          "nip" => "196108241991031002"
        ],
        [
          "name" => "Gabriel  Rago",
          "nip" => "196603231992031002"
        ],
        [
          "name" => "Deny Felix Porawouw",
          "nip" => "196311041986031001"
        ],
        [
          "name" => "Jemmy Runtuwene",
          "nip" => "196809021991031003"
        ],
        [
          "name" => "David Lawe Koten",
          "nip" => "196504101991031001"
        ],
        [
          "name" => "Abdul Haris",
          "nip" => "196412311990031014"
        ],
        [
          "name" => "Teguh Purnomo",
          "nip" => "197301281993031001"
        ],
        [
          "name" => "Heymans Tamaka",
          "nip" => "196601231990061001"
        ],
        [
          "name" => "I Nengah Wardana",
          "nip" => "197212151993031002"
        ],
        [
          "name" => "I Dewa Made Merthe Yasa",
          "nip" => "196905161991011001"
        ],
        [
          "name" => "Aam Mahmud",
          "nip" => "197201191993031001"
        ],
        [
          "name" => "Iyus Rushana",
          "nip" => "196106261991031002"
        ],
        [
          "name" => "Iwan Amat",
          "nip" => "197503251995031001"
        ],
        [
          "name" => "Yappie Fredrik Albert Rombot",
          "nip" => "197404051994031003"
        ],
        [
          "name" => "I Gede Sudarsana",
          "nip" => "196701251992031002"
        ],
        [
          "name" => "Iyan Mulyana, S.T.",
          "nip" => "197012021992031002"
        ],
        [
          "name" => "Kushendratno, S.T., M.T.",
          "nip" => "197810292006041001"
        ],
        [
          "name" => "Yasa Suparman, S.T., M.Si.",
          "nip" => "197901072006041001"
        ],
        [
          "name" => "M. Nizar Firmansyah, S.T., M.T.",
          "nip" => "198008062006041002"
        ],
        [
          "name" => "Amalfi Omang, S.Si., M. Phil.",
          "nip" => "197909182008011001"
        ],
        [
          "name" => "Rahayu Robiana, S.Si., M.Si.",
          "nip" => "198210042006041002"
        ],
        [
          "name" => "Yudhi Wahyudi, S.T., M.T.",
          "nip" => "197410052006041001"
        ],
        [
          "name" => "Aditya Sebastian Andreas, S.Si., M.Si.",
          "nip" => "198012192008011001"
        ],
        [
          "name" => "Novianti Indrastuti, S.Si., M.Si.",
          "nip" => "198111022008012001"
        ],
        [
          "name" => "Haunan Afif, S.Si.",
          "nip" => "198710152010121003"
        ],
        [
          "name" => "Imam Catur Priambodo, S.Si.",
          "nip" => "198711072010121002"
        ],
        [
          "name" => "Sucahyo Adi, S.T.",
          "nip" => "196404041992031001"
        ],
        [
          "name" => "Syegi Lenarahmi Kunrat, S.Si.",
          "nip" => "198203092006042001"
        ],
        [
          "name" => "Yana Karyana, S.Kom.",
          "nip" => "196812291993031003"
        ],
        [
          "name" => "Slamet Sutrisno, B.Sc",
          "nip" => "196009291985031001"
        ],
        [
          "name" => "Wati Rasmawati, S.A.P.",
          "nip" => "196804211992032001"
        ],
        [
          "name" => "Koti Kittyakara, S.E.",
          "nip" => "198103252008012001"
        ],
        [
          "name" => "Asep Ginanjar Mangkuwinata, SAB.",
          "nip" => "196903181992031002"
        ],
        [
          "name" => "Rohman",
          "nip" => "196211291981031001"
        ],
        [
          "name" => "Dhini Novarianayati, A.Md.",
          "nip" => "197611252002122001"
        ],
        [
          "name" => "Yudia Prama Tatipang",
          "nip" => "196808101990031002"
        ],
        [
          "name" => "Mailudu Mugu",
          "nip" => "196404091984031002"
        ],
        [
          "name" => "Petrus Tupe Taran",
          "nip" => "196204141984031004"
        ],
        [
          "name" => "Bernadus Taut",
          "nip" => "196512301991031002"
        ],
        [
          "name" => "Hery Prasetyo",
          "nip" => "196809281997031001"
        ],
        [
          "name" => "Taufan Barham",
          "nip" => "197407021997031002"
        ],
        [
          "name" => "Safei",
          "nip" => "196505131992031001"
        ],
        [
          "name" => "Agusthinus Bili da Silva",
          "nip" => "196308191984031001"
        ],
        [
          "name" => "Suparno",
          "nip" => "196302161986031003"
        ],
        [
          "name" => "Hery Kuswandarto, A.Md.",
          "nip" => "197403272002121001"
        ],
        [
          "name" => "Bujang",
          "nip" => "196107041986031002"
        ],
        [
          "name" => "Bambang Sugiono",
          "nip" => "197011191995031001"
        ],
        [
          "name" => "Johan Kusuma, A. Md.",
          "nip" => "198305152010121001"
        ],
        [
          "name" => "Semtinusa Aku Pandu",
          "nip" => "197212311996031001"
        ],
        [
          "name" => "Andi Suardi",
          "nip" => "196811101996031003"
        ],
        [
          "name" => "Ilham Mardikayanta, A.Md.",
          "nip" => "198408102009121004"
        ],
        [
          "name" => "Suparjan, A.Md.",
          "nip" => "198701022009121001"
        ],
        [
          "name" => "Ahmad Subhan Nur Fajidi, A.Md.",
          "nip" => "198205112009121002"
        ],
        [
          "name" => "Maryani, A.Md.",
          "nip" => "197903072009121002"
        ],
        [
          "name" => "Bambang Wibowo",
          "nip" => "196904231992031001"
        ],
        [
          "name" => "Fransiskus Xaverius Masan",
          "nip" => "196905021992031012"
        ],
        [
          "name" => "Suparmo",
          "nip" => "196103061986031003"
        ],
        [
          "name" => "Kisroh",
          "nip" => "196505241993031001"
        ],
        [
          "name" => "Harry Cahyono, S.Si.",
          "nip" => "198507082010121002"
        ],
        [
          "name" => "Tumpal D. Sinaga, S.Si.",
          "nip" => "198503232009011002"
        ],
        [
          "name" => "I Gede Bagiarta, S.T.",
          "nip" => "197205261993031001"
        ],
        [
          "name" => "Widya Ika Retnoningtyas, S.T.",
          "nip" => "198310232009012002"
        ],
        [
          "name" => "Imun Maemunah, S.T., M.Si.",
          "nip" => "197611112006042001"
        ],
        [
          "name" => "Heruningtyas Desi Purnamasari, S.T.",
          "nip" => "199012042014022003"
        ],
        [
          "name" => "Lestari Agustiningtyas, S.T.",
          "nip" => "198808282014022003"
        ],
        [
          "name" => "Merry Christina Natalia, S.T.",
          "nip" => "198912222014022001"
        ],
        [
          "name" => "Yohandi Kristiawan, S.T.",
          "nip" => "198905292014021004"
        ],
        [
          "name" => "Iskandar, S.T.",
          "nip" => "198904182014021004"
        ],
        [
          "name" => "Asman",
          "nip" => "196308081986031004"
        ],
        [
          "name" => "Tri Astuti Rahmawati, S.S.T.",
          "nip" => "198305282006042001"
        ],
        [
          "name" => "Andika Bayu Aji, S.T.",
          "nip" => "198710102014021002"
        ],
        [
          "name" => "Asep Koswara Somantri",
          "nip" => "196311141988031001"
        ],
        [
          "name" => "Badarudin",
          "nip" => "197012101992031001"
        ],
        [
          "name" => "Tuti Rusmiyati",
          "nip" => "196805221993032001"
        ],
        [
          "name" => "Ayi Sumiasih",
          "nip" => "196107031987032001"
        ],
        [
          "name" => "Eti Rohaeti",
          "nip" => "196303251988032001"
        ],
        [
          "name" => "Dadang Umyati",
          "nip" => "196304271993031002"
        ],
        [
          "name" => "Yayo",
          "nip" => "196007061984031002"
        ],
        [
          "name" => "Iwan Ridwan",
          "nip" => "196112261989031002"
        ],
        [
          "name" => "Dadan Suchendar",
          "nip" => "196712161989031002"
        ],
        [
          "name" => "Refly Paulus Aror, S.IP.",
          "nip" => "198310272009021001"
        ],
        [
          "name" => "Bambang Walayanto, S.T.",
          "nip" => "196110242007011001"
        ],
        [
          "name" => "Asikin Saputra, S.E.",
          "nip" => "196905071992031002"
        ],
        [
          "name" => "Roni Hernadi, S.E.",
          "nip" => "196605161993031001"
        ],
        [
          "name" => "Roni Taufiqurrohman, S.T.",
          "nip" => "197011282005021001"
        ],
        [
          "name" => "Beti Mulyati",
          "nip" => "196111281988032001"
        ],
        [
          "name" => "Dewi Subektiningsih",
          "nip" => "197005091994032001"
        ],
        [
          "name" => "Agung Sih Damayanti, S.T.",
          "nip" => "198105252006042001"
        ],
        [
          "name" => "Gunawan Setia",
          "nip" => "196701081987031002"
        ],
        [
          "name" => "Mohamad Iskak",
          "nip" => "196203201990091001"
        ],
        [
          "name" => "Juanda",
          "nip" => "196209071992031001"
        ],
        [
          "name" => "Rahmanto",
          "nip" => "196705081992031001"
        ],
        [
          "name" => "Dedy Rohendi",
          "nip" => "196707131992031003"
        ],
        [
          "name" => "Heri Isep Rohmana",
          "nip" => "196802021992031001"
        ],
        [
          "name" => "Wahidin Akbar",
          "nip" => "196712181991031001"
        ],
        [
          "name" => "Charles Yulius Orkris Pah",
          "nip" => "196807091993031002"
        ],
        [
          "name" => "Sulus Setiono, S.T.",
          "nip" => "198007142006041001"
        ],
        [
          "name" => "Anna Mathovanie, S.T.",
          "nip" => "198212172006042001"
        ],
        [
          "name" => "Hartati, S.T.",
          "nip" => "198104172006042002"
        ],
        [
          "name" => "Alzwar Nurmanaji, A.Md.",
          "nip" => "198509132009121001"
        ],
        [
          "name" => "Lalu Zulkarnain",
          "nip" => "197012301995031001"
        ],
        [
          "name" => "Ridwan Djalil",
          "nip" => "197305051995031002"
        ],
        [
          "name" => "Mukdas Sofian, A.Md.",
          "nip" => "197502042009121001"
        ],
        [
          "name" => "Sujani",
          "nip" => "196409121997031002"
        ],
        [
          "name" => "Fransiskus Senda Wangge",
          "nip" => "196512311990031010"
        ],
        [
          "name" => "Ferry Rusmawan, A.Md.",
          "nip" => "198602082009121002"
        ],
        [
          "name" => "Nur Rokhman Hidayat, A.Md.",
          "nip" => "198704192009121002"
        ],
        [
          "name" => "Deny Mardiono, A.Md.",
          "nip" => "198408262009121004"
        ],
        [
          "name" => "Edi Ruhaedi, A.Md.",
          "nip" => "197708022009121002"
        ],
        [
          "name" => "Ahmad Sopari",
          "nip" => "196705152002121002"
        ],
        [
          "name" => "Mutaharlin",
          "nip" => "196408071996031001"
        ],
        [
          "name" => "Sugeng Santosa",
          "nip" => "196512071986031004"
        ],
        [
          "name" => "Abdul Farid N. Baginda",
          "nip" => "197111231996031001"
        ],
        [
          "name" => "Sandy Levran Manengkey",
          "nip" => "197604301997031001"
        ],
        [
          "name" => "Soepartana",
          "nip" => "197104092002121001"
        ],
        [
          "name" => "Jajat Sudrajat, A.Md.",
          "nip" => "198309202009121003"
        ],
        [
          "name" => "Fransiskus Boli Roma",
          "nip" => "197701102002121002"
        ],
        [
          "name" => "Johan Hasan",
          "nip" => "197307311994031002"
        ],
        [
          "name" => "Mulyadi",
          "nip" => "196507202005021001"
        ],
        [
          "name" => "Hartanto Prawiro, A.Md.",
          "nip" => "198607312009121003"
        ],
        [
          "name" => "Dhanu Dwi Ariadi R, A.Md.",
          "nip" => "198201042009121001"
        ],
        [
          "name" => "Stanislaus Ara Kian, A.Md.",
          "nip" => "198308252009121003"
        ],
        [
          "name" => "Ananto Bagus Kuncoro",
          "nip" => "197103202005021001"
        ],
        [
          "name" => "Surip",
          "nip" => "197202132003121001"
        ],
        [
          "name" => "R. Fajar Anugrah, A. Md.",
          "nip" => "198205102010121001"
        ],
        [
          "name" => "Apit Wagianto, A.Md.",
          "nip" => "198906052010121003"
        ],
        [
          "name" => "Luruh",
          "nip" => "196508121991031003"
        ],
        [
          "name" => "Djuhdi Djuhara, A.P.",
          "nip" => "197007242003121001"
        ],
        [
          "name" => "Pandu Adi Minarno, S.T.",
          "nip" => "198712072014021003"
        ],
        [
          "name" => "Raditya Putra, S.T.",
          "nip" => "198608242014021003"
        ],
        [
          "name" => "David Adriansyah, S.T.",
          "nip" => "198510172014021002"
        ],
        [
          "name" => "Kibar Muhammad Suryadana, S.T.",
          "nip" => "198710222015031001"
        ],
        [
          "name" => "Niken Angga Rukmini, S.T.",
          "nip" => "198911272015032005"
        ],
        [
          "name" => "Hilma Alfianti, S.Si.",
          "nip" => "198510082014022001"
        ],
        [
          "name" => "Hany Nur Ananda, S.H.",
          "nip" => "198503272015032001"
        ],
        [
          "name" => "Ahmad Hidayat, S.E.",
          "nip" => "196909271992031002"
        ],
        [
          "name" => "Annisa Prastanti, S.T.",
          "nip" => "198911032015032004"
        ],
        [
          "name" => "Novia Antika Anggraeni, S.Si.",
          "nip" => "199108212015032003"
        ],
        [
          "name" => "Iqbal Eras Putra, S.T.",
          "nip" => "198703072015031003"
        ],
        [
          "name" => "Wieke Pratiwi, S.T.",
          "nip" => "199109162015032007"
        ],
        [
          "name" => "Arianne Pingkan Lewu, S.T.",
          "nip" => "199104242015032006"
        ],
        [
          "name" => "Malia Adityarani, S.T.",
          "nip" => "198810102015032005"
        ],
        [
          "name" => "Sulistiyani, S.Si.",
          "nip" => "198710262015032006"
        ],
        [
          "name" => "Pamela, S.T.",
          "nip" => "199003132015032002"
        ],
        [
          "name" => "Martanto, S.T.",
          "nip" => "198803152015031005"
        ],
        [
          "name" => "Wilfridus F. S. Banggur, S.T.",
          "nip" => "198502112015031002"
        ],
        [
          "name" => "Muhammad Nazir",
          "nip" => "197702252003121001"
        ],
        [
          "name" => "Sarjan Roboke",
          "nip" => "197504052002121002"
        ],
        [
          "name" => "Kawa Sungkawa",
          "nip" => "196904122003121001"
        ],
        [
          "name" => "Pardianto",
          "nip" => "198112132006041002"
        ],
        [
          "name" => "Hendra Supratman  Putra, Rd.",
          "nip" => "198602262005021001"
        ],
        [
          "name" => "Didi Wahyudi Pernama Putra Bina",
          "nip" => "198702022006041002"
        ],
        [
          "name" => "Hendri Deratama, A.Md.",
          "nip" => "198707302014021003"
        ],
        [
          "name" => "Hamdani",
          "nip" => "196801152005021001"
        ],
        [
          "name" => "Yosef Suryanto",
          "nip" => "197902042005021003"
        ],
        [
          "name" => "Antonius Sigit Tripambudi",
          "nip" => "196812112005021001"
        ],
        [
          "name" => "S. Mamory",
          "nip" => "197805272005021001"
        ],
        [
          "name" => "Armando Manguleh, A.Md.",
          "nip" => "198610082014021002"
        ],
        [
          "name" => "Ahmad Rifandi, A.Md.",
          "nip" => "198806282014021003"
        ],
        [
          "name" => "Ihsan Nopa Abadi",
          "nip" => "197701152006041002"
        ],
        [
          "name" => "Yuli Ramatulloh",
          "nip" => "198707212009121002"
        ],
        [
          "name" => "Rachmad Widyo Laksono",
          "nip" => "198903272010121001"
        ],
        [
          "name" => "Mukijo",
          "nip" => "198212172009121004"
        ],
        [
          "name" => "Aziz Yuliawan",
          "nip" => "198707032009121002"
        ],
        [
          "name" => "Bernadus Paknianiwewan",
          "nip" => "197004192003121002"
        ],
        [
          "name" => "Herman Yosef S Mboro",
          "nip" => "197905242009121001"
        ],
        [
          "name" => "Seprius",
          "nip" => "198109202009011002"
        ],
        [
          "name" => "Warseno",
          "nip" => "198101032005021009"
        ],
        [
          "name" => "Suwarno",
          "nip" => "197111042005021001"
        ],
        [
          "name" => "Burhan Alethea, A.Md.",
          "nip" => "198501112014021003"
        ],
        [
          "name" => "Arif Cahyo Purnomo, A.Md.",
          "nip" => "198805312014021001"
        ],
        [
          "name" => "Deri Al Hidayat, A.Md.",
          "nip" => "199012292014021003"
        ],
        [
          "name" => "Anggi Nuryo Saputro, A.Md.",
          "nip" => "198508092014021001"
        ],
        [
          "name" => "Dedy Supriady",
          "nip" => "196005031989031001"
        ],
        [
          "name" => "Tata",
          "nip" => "196201021989031002"
        ],
        [
          "name" => "Sapari Dwiyono",
          "nip" => "197403182005021001"
        ],
        [
          "name" => "Much. Rozin",
          "nip" => "198008262005021001"
        ],
        [
          "name" => "Lilih",
          "nip" => "196006131983031002"
        ],
        [
          "name" => "Fandy Ronald Rumimper",
          "nip" => "198609102006041001"
        ],
        [
          "name" => "Jumono",
          "nip" => "197011202007011001"
        ],
        [
          "name" => "Vinsensius Tuku",
          "nip" => "198105032010121002"
        ],
        [
          "name" => "Anwar Sidiq",
          "nip" => "198612202009121004"
        ],
        [
          "name" => "Tri Mujiyanta",
          "nip" => "197109042007011001"
        ],
        [
          "name" => "Zihismal",
          "nip" => "198310052009121004"
        ],
        [
          "name" => "Marsianus Meo Lako",
          "nip" => "198407312009121002"
        ],
        [
          "name" => "Yuliana Jaya",
          "nip" => "198204302010121001"
        ],
        [
          "name" => "Marjan",
          "nip" => "198612042010121001"
        ],
        [
          "name" => "Ujang Jajat Solehudin",
          "nip" => "198605172010121002"
        ],
        [
          "name" => "Julius Ramopolii",
          "nip" => "198207302010121004"
        ],
        [
          "name" => "Ahmad Muhamad Nabawi",
          "nip" => "198902192009011001"
        ],
        [
          "name" => "Yuvensius Nggaa",
          "nip" => "197704092009121001"
        ],
        [
          "name" => "Asep Saefuloh",
          "nip" => "198309052010121001"
        ],
        [
          "name" => "Razali",
          "nip" => "196805042005021001"
        ],
        [
          "name" => "Wilson Wuri Wuthun",
          "nip" => "198301042009011003"
        ],
        [
          "name" => "Kuswarno",
          "nip" => "198104232009121002"
        ],
        [
          "name" => "Fredianto Anthon Richard Korompis",
          "nip" => "198702122010121004"
        ],
        [
          "name" => "Syarif Abdul Manaf, A.Md.",
          "nip" => "198706202009011002"
        ],
        [
          "name" => "Windi Cahya Untung",
          "nip" => "198503072010121002"
        ],
        [
          "name" => "Alfret Frangky Wenas",
          "nip" => "198408232009121004"
        ],
        [
          "name" => "Rubiyo",
          "nip" => "198510092010121002"
        ],
        [
          "name" => "Subandrio K Puyo",
          "nip" => "198703132009121003"
        ],
        [
          "name" => "Indra Saputra, A.Md",
          "nip" => "198610282009121002"
        ],
        [
          "name" => "Armen Putra",
          "nip" => "198408202009121002"
        ],
        [
          "name" => "Tannisa Aprianti, A.Md.",
          "nip" => "199404192015032001"
        ],
        [
          "name" => "Steve Stuward Muaja Rotti, A.Md.",
          "nip" => "198809242018011002"
        ],
        [
          "name" => "Rasyidin, A.Md.",
          "nip" => "198807282018011001"
        ],
        [
          "name" => "Yeremias Kristianto Pugel, A.Md.",
          "nip" => "199001212018011003"
        ],
        [
          "name" => "Aprianto Mokotoloy, A.Md.",
          "nip" => "199004212018011001"
        ],
        [
          "name" => "Rio Andika, A.Md.",
          "nip" => "199111152018011001"
        ],
        [
          "name" => "Andrik Kurnia Adi Pratama, A.Md.",
          "nip" => "199303172018011002"
        ],
        [
          "name" => "Anselmus Bobyson Lamanepa, A.Md.",
          "nip" => "198804212018011003"
        ],
        [
          "name" => "Indra Syahputra, A.Md.",
          "nip" => "198904012018011003"
        ],
        [
          "name" => "Tommy Luhut Marbun, A.Md.",
          "nip" => "199012032018011002"
        ],
        [
          "name" => "Victorius Antonio De Fretis, A.Md.",
          "nip" => "198906052018011001"
        ],
        [
          "name" => "Robertus Belarminus Dua, A.Md.",
          "nip" => "199309172018011004"
        ],
        [
          "name" => "Nizwaril Hamdi, A.Md.",
          "nip" => "199406082018011005"
        ],
        [
          "name" => "Nofiangga Tristi Kurniawan, A.Md.",
          "nip" => "198911272018011001"
        ],
        [
          "name" => "Syastradin",
          "nip" => "196812311989031002"
        ],
        [
          "name" => "Wagino",
          "nip" => "196205201993031001"
        ],
        [
          "name" => "Albertus Galih Prasida Kastawa, A.Md.",
          "nip" => "199210132015031001"
        ],
        [
          "name" => "Dany Erlangga Yosa Putra, A.Md.",
          "nip" => "199407172015031001"
        ],
        [
          "name" => "Benny Setyawan, A.Md.",
          "nip" => "198703222015031003"
        ],
        [
          "name" => "Kachfi Somantri, A.Md.",
          "nip" => "199001142015031007"
        ],
        [
          "name" => "Achmad Nurin Kausar, A.Md.",
          "nip" => "198910142015031006"
        ],
        [
          "name" => "Moh Nurul Asrori, A.Md.",
          "nip" => "198911202015031004"
        ],
        [
          "name" => "Yudha Pratama, A.Md.",
          "nip" => "198903102015031007"
        ],
        [
          "name" => "Muhammad Rusdi, A.Md.",
          "nip" => "199011142015031002"
        ],
        [
          "name" => "Rudra Wibowo, A.Md.",
          "nip" => "198610212015031005"
        ],
        [
          "name" => "Bagus Puguh Wibowo, A.Md.",
          "nip" => "198511272015031003"
        ],
        [
          "name" => "Fransiskus Desales Molo, A.Md.",
          "nip" => "198701242015031004"
        ],
        [
          "name" => "Gradita Trihadi, A.Md.",
          "nip" => "198911112015031004"
        ],
        [
          "name" => "Diyan Muharomidin, A.Md.",
          "nip" => "198808192015031004"
        ],
        [
          "name" => "Nur Hudha, A.Md.",
          "nip" => "198811202015031003"
        ],
        [
          "name" => "Aditya Gurasali, A.Md.",
          "nip" => "198905302015031007"
        ],
        [
          "name" => "Mazrifani Fajar Rozali, A.Md.",
          "nip" => "199006062015031005"
        ],
        [
          "name" => "Yuda Prinardita Pura, A.Md.",
          "nip" => "198604182015031001"
        ],
        [
          "name" => "Fahrul Roji, A.Md.",
          "nip" => "199012112015031002"
        ],
        [
          "name" => "Yadi Yuliandi, A.Md.",
          "nip" => "198606132015031001"
        ],
        [
          "name" => "Erlangga Ario Seto, A.Md.",
          "nip" => "199208272015031004"
        ],
        [
          "name" => "Wahyu Wijayanto, A.Md.",
          "nip" => "198807142015031003"
        ],
        [
          "name" => "Megian Nugraha, A.Md.",
          "nip" => "199105312015031003"
        ],
        [
          "name" => "Nurul Husaeni, A.Md.",
          "nip" => "198802282015031004"
        ],
        [
          "name" => "Oo Sudrajat",
          "nip" => "196212311992031007"
        ],
        [
          "name" => "Wiwin Rusmayani",
          "nip" => "197510122007012002"
        ],
        [
          "name" => "Asep Andi Sopyan",
          "nip" => "197610272008111001"
        ],
        [
          "name" => "Ahmad",
          "nip" => "196101031984031002"
        ],
        [
          "name" => "Ngadiyono",
          "nip" => "197011142007011027"
        ],
        [
          "name" => "Tudi Untoro",
          "nip" => "197109092008111001"
        ],
        [
          "name" => "Sugeng Trismanto",
          "nip" => "196507042009101001"
        ],
        [
          "name" => "Sri Darwati, A.Md.",
          "nip" => "198006292009102001"
        ],
        [
          "name" => "Martinus Mamun Doni, A.Md.Kom.",
          "nip" => "197807222006041001"
        ],
        [
          "name" => "Emanuel Rofinus Bere, A.Md.Kom.",
          "nip" => "198512022009011001"
        ],
        [
          "name" => "Hadi Purwoko, A.Md.Kom.",
          "nip" => "198510052009011003"
        ],
        [
          "name" => "Joko Widana",
          "nip" => "196507072007011002"
        ],
        [
          "name" => "Sonni Permana",
          "nip" => "197806182007011001"
        ],
        [
          "name" => "Wardiman",
          "nip" => "196505132007011002"
        ],
        [
          "name" => "Susanta",
          "nip" => "197104092007011002"
        ],
        [
          "name" => "Arifin",
          "nip" => "196201032007011001"
        ],
        [
          "name" => "Sigit Maryono",
          "nip" => "198010022008111001"
        ],
        [
          "name" => "Juandi",
          "nip" => "196306212007011001"
        ],
        [
          "name" => "Purwito",
          "nip" => "197507052009101001"
        ],
        [
          "name" => "Musmulyadi",
          "nip" => "198302052009121001"
        ],
        [
          "name" => "Munawir I. Hi. M. Salelang",
          "nip" => "198701152009011001"
        ],
        [
          "name" => "Kristianus Yosafat",
          "nip" => "198710122009011001"
        ],
        [
          "name" => "Etwin Randi Porawouw",
          "nip" => "198808302009121001"
        ],
        [
          "name" => "Jandri Arnold Wolla",
          "nip" => "198201172009121001"
        ],
        [
          "name" => "Mohammad Isra",
          "nip" => "199201312014021001"
        ],
        [
          "name" => "Ade Yasser Akhmad Purwata",
          "nip" => "199210232014021001"
        ],
        [
          "name" => "Sigit Rian Alfian",
          "nip" => "199005152014021002"
        ],
        [
          "name" => "Yana Supriatna",
          "nip" => "197407102008111001"
        ],
        [
          "name" => "Maman Suherman",
          "nip" => "197204142008111001"
        ],
        [
          "name" => "Agustinus Ola Bainauk Ratimakin",
          "nip" => "198203062009011003"
        ],
        [
          "name" => "Ghufron Alwi",
          "nip" => "199105092014021001"
        ],
        [
          "name" => "Andri Yunianto",
          "nip" => "199406112014021001"
        ],
        [
          "name" => "Yohanes Paulus Wisang",
          "nip" => "199012272018011002"
        ],
        [
          "name" => "Irwan Ka Uman",
          "nip" => "199104032018011002"
        ],
        [
          "name" => "Farhan Azhari",
          "nip" => "199112182018011002"
        ],
        [
          "name" => "Safrisal Rustam",
          "nip" => "198810182018011001"
        ],
        [
          "name" => "Purwadi Sucipto",
          "nip" => "199805032018011002"
        ],
        [
          "name" => "Axl Roeroe",
          "nip" => "199403022018011002"
        ],
        [
          "name" => "Rivaldi Hasan",
          "nip" => "199606252018011001"
        ],
        [
          "name" => "Asep Antoni",
          "nip" => "199309032018011002"
        ],
        [
          "name" => "Yudi Maulana",
          "nip" => "199610132018011001"
        ],
        [
          "name" => "Triyanto",
          "nip" => "199005052018011003"
        ],
        [
          "name" => "Wahyu Ardi Setiawan",
          "nip" => "199105192015031002"
        ],
        [
          "name" => "Anwar Mucklisin",
          "nip" => "199206172015031004"
        ],
        [
          "name" => "Dedi Nurani",
          "nip" => "198802172015031006"
        ],
        [
          "name" => "Budi Marwanto",
          "nip" => "198511012015031001"
        ],
        [
          "name" => "Wahyu Andrian Kusuma",
          "nip" => "199209032015031001"
        ],
        [
          "name" => "Ardi",
          "nip" => "198601152015031003"
        ],
        [
          "name" => "Tri Yemdi S. Noer",
          "nip" => "198703072015031006"
        ],
        [
          "name" => "Adzan Anugrah Indiarsyah",
          "nip" => "199506022015031001"
        ],
        [
          "name" => "Nur Hidayat",
          "nip" => "199603182015031001"
        ],
        [
          "name" => "Jeheskiel Harto Proklami Montolalu",
          "nip" => "199108162015031002"
        ],
        [
          "name" => "Budi Santoso",
          "nip" => "198902072015031002"
        ],
        [
          "name" => "Bambang Giat Gunawan",
          "nip" => "198812092015031004"
        ],
        [
          "name" => "Anjana",
          "nip" => "196304011989031002"
        ],
        [
          "name" => "Rachmat",
          "nip" => "196304301991031001"
        ],
        [
          "name" => "Agus Supriatna",
          "nip" => "196010022006041007"
        ],
        [
          "name" => "Suraji",
          "nip" => "197302262007011001"
        ],
        [
          "name" => "Poniman",
          "nip" => "196106062007011003"
        ],
        [
          "name" => "Dedi Saepudin",
          "nip" => "196205052007011002"
        ],
        [
          "name" => "Sarwanta",
          "nip" => "196605242007011001"
        ],
        [
          "name" => "Endang Juhandi",
          "nip" => "196805142007011002"
        ],
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update data user MAGMA';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Updating Data Users....');

        foreach ($this->users as $user) {
            User::updateOrCreate([
                'nip' => $user['nip'],
            ],[
                'name' => $user['name'],
                'password' => 'esdm1234',
            ]);
        }

        $this->info('Update Data berhasil');
    }
}
<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\Ecosystem;
use App\Models\LearningRule;
use App\Models\Organism;
use App\Models\QuizPuzzle;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class EksploraSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['basic' => [20, 'Materi dasar'], 'ecosystem' => [20, 'Satu ekosistem'], 'organism' => [5, 'Satu organisme'], 'comparison' => [10, 'Perbandingan pertama'], 'conservation' => [5, 'Satu materi pelestarian'], 'quiz_max' => [20, 'Poin maksimal setiap jenis kuis'], 'puzzle' => [15, 'Puzzle selesai'], 'level_step' => [100, 'XP setiap level']] as $key => [$value,$description]) {
            LearningRule::updateOrCreate(compact('key'), compact('value', 'description'));
        }
        $ecosystems = [
            ['rawa-lebak', 'Rawa Lebak', 'Rawa air tawar yang mengikuti irama hujan dan luapan sungai.',
                'Air tawar; genangan naik saat musim hujan dan menyusut saat kemarau.', 'Rumput rawa, teratai, dan tumbuhan tepi perairan.',
                'Terletak di dataran rendah. Lama dan kedalaman genangan berbeda-beda.',
                'Tumbuhan terapung memiliki bagian berongga yang membantu mengapung. Haruan dapat mengambil oksigen dari udara.',
                'Menampung luapan air, menjadi tempat ikan berkembang biak, dan mendukung perikanan masyarakat.',
                'Sampah, penangkapan ikan merusak, dan penimbunan rawa mengurangi ruang hidup organisme.',
                'Jaga saluran air alami, hindari racun ikan, dan kumpulkan sampah tanpa mengganggu sarang hewan.',
                'Rawa yang tampak kering pada suatu musim dapat kembali menjadi habitat air saat hujan.'],
            ['rawa-pasang-surut', 'Rawa Pasang Surut', 'Lahan basah yang tinggi airnya dipengaruhi pasang dan surut.',
                'Ketinggian air berubah mengikuti pasang surut; kadar garam bergantung pada jarak dari laut.', 'Nipah di bagian payau dan vegetasi rawa di bagian yang lebih tawar.',
                'Terhubung dengan sungai atau muara. Sebagian tanah mengandung bahan pembentuk asam jika dikeringkan.',
                'Nipah tumbuh di substrat berlumpur. Organisme menyesuaikan diri dengan perubahan tinggi air.',
                'Menahan air, menyediakan habitat asuhan biota, dan mendukung kegiatan masyarakat di tepian sungai.',
                'Drainase yang tidak tepat, limbah, serta pembukaan lahan dapat mengubah kualitas air dan tanah.',
                'Pertahankan vegetasi tepi saluran dan kelola air dengan hati-hati. Jangan membuang limbah ke sungai.',
                'Pasang air sungai tidak selalu berarti airnya asin; pengaruh laut berbeda di tiap lokasi.'],
            ['rawa-gambut', 'Rawa Gambut', 'Ekosistem dengan timbunan sisa tumbuhan yang terurai sangat lambat.',
                'Air sering berwarna cokelat karena bahan organik, cenderung asam, dan miskin unsur hara.', 'Pohon rawa gambut dan tumbuhan yang toleran terhadap kondisi asam.',
                'Tanahnya kaya bahan organik dan menyimpan banyak air serta karbon.',
                'Tumbuhan toleran terhadap tanah jenuh air dan nutrisi terbatas. Tidak semua organisme tahan air asam.',
                'Menyimpan karbon, menjaga cadangan air, serta menjadi habitat beragam organisme.',
                'Pengeringan membuat gambut rentan terbakar. Kebakaran dan pembukaan lahan merusak simpanan karbon.',
                'Jaga gambut tetap basah, hindari membakar lahan, dan dukung pemulihan vegetasi asli.',
                'Gambut terbentuk sangat lambat sehingga kerusakannya sulit dipulihkan dalam waktu singkat.'],
            ['hutan-mangrove', 'Hutan Mangrove', 'Hutan di pesisir berlumpur yang beradaptasi dengan pasang surut.',
                'Air payau hingga asin, dengan genangan berkala saat pasang.', 'Bakau dan nipah pada zona yang sesuai.',
                'Berada di pesisir atau muara yang terlindung. Akar menjebak sedimen dan menyediakan tempat berlindung.',
                'Akar tunjang bakau membantu menopang pohon pada lumpur lunak. Adaptasi garam berbeda antarjenis mangrove.',
                'Meredam energi gelombang, menyimpan karbon, dan menjadi daerah asuhan ikan serta biota pesisir.',
                'Alih fungsi pesisir, sampah plastik, dan penebangan mengurangi perlindungan pantai.',
                'Lindungi mangrove yang ada; penanaman harus sesuai jenis, ketinggian pasang, dan kondisi lokasi.',
                'Mangrove bukan satu jenis pohon, melainkan kelompok tumbuhan dengan berbagai adaptasi.'],
            ['sungai', 'Sungai', 'Aliran air yang menghubungkan wilayah hulu, dataran rendah, dan muara.',
                'Air mengalir; arus, kedalaman, dan kekeruhan berubah menurut lokasi serta musim.', 'Tumbuhan riparian di tepi sungai dan tumbuhan air pada bagian berarus lambat.',
                'Mengangkut air dan sedimen. Sungai Barito dan Martapura merupakan bagian penting bentang lahan Kalimantan Selatan.',
                'Bentuk tubuh ikan membantu bergerak dalam air. Akar tumbuhan tepi sungai membantu mencengkeram tanah.',
                'Menyediakan habitat, sumber air, jalur transportasi, dan penghubung antarekosistem.',
                'Limbah rumah tangga, erosi, dan hilangnya vegetasi tepi sungai menurunkan kualitas habitat.',
                'Kurangi sampah, rawat vegetasi bantaran, dan gunakan air secara bijak. Amati sungai dari tempat aman.',
                'Kegiatan di hulu dapat memengaruhi kualitas air jauh di hilir.'],
        ];
        foreach ($ecosystems as [$slug,$name,$summary,$water,$vegetation,$characteristics,$adaptation,$benefits,$threats,$conservation,$fact]) {
            Ecosystem::updateOrCreate(['slug' => $slug], ['name' => $name, 'summary' => $summary, 'image' => 'images/'.$slug.'.svg',
                'content' => compact('water', 'vegetation', 'characteristics', 'adaptation', 'benefits', 'threats', 'conservation', 'fact')]);
        }
        foreach (Ecosystem::all() as $e) {
            QuizPuzzle::updateOrCreate(['ecosystem_id' => $e->id], ['name' => 'Puzzle '.$e->name, 'size' => 3]);
        }
        // Habitat links are educational examples, not a claim of occurrence at every local site.
        $organisms = [
            ['ikan-haruan', 'Ikan Haruan', 'Channa striata', 'fauna', ['rawa-lebak', 'sungai'],
                'Ikan air tawar yang juga dikenal sebagai gabus. Haruan merupakan pemangsa di perairan berarus lambat.',
                'Tubuh memanjang, kepala lebar, punggung gelap dan bagian bawah lebih pucat.',
                'Perairan tawar dangkal, rawa, dan bagian sungai berarus lambat.',
                'Memakan ikan kecil, serangga, dan hewan air lain.',
                'Memiliki alat pernapasan tambahan untuk mengambil oksigen dari udara; tetap memerlukan kondisi lembap.',
                'Membantu menjaga jaring makanan dan dimanfaatkan sebagai sumber pangan.', 'https://www.fishbase.org/summary/343'],
            ['ikan-papuyu', 'Ikan Papuyu', 'Anabas testudineus', 'fauna', ['rawa-lebak', 'sungai'],
                'Ikan air tawar yang mampu hidup di perairan dengan oksigen terlarut rendah.',
                'Tubuh pipih ke samping, bersisik, dan memiliki duri pada siripnya.',
                'Rawa air tawar, kolam, serta bagian sungai yang tenang.',
                'Memakan beragam bahan makanan, termasuk hewan kecil dan bahan tumbuhan.',
                'Organ labirin membantu mengambil oksigen dari udara di atas permukaan air.',
                'Bagian dari jaring makanan dan sumber pangan masyarakat.', 'https://www.fishbase.org/summary/495'],
            ['ikan-sepat-rawa', 'Ikan Sepat Rawa', 'Trichopodus trichopterus', 'fauna', ['rawa-lebak', 'sungai'],
                'Ikan kecil perairan tawar tenang dengan sirip perut menyerupai benang.',
                'Tubuh pipih, dua bercak gelap pada sisi tubuh dan sirip perut panjang.',
                'Rawa bervegetasi dan perairan tawar berarus lambat.',
                'Hewan air kecil, plankton, dan bahan organik.',
                'Organ labirin membantu bernapas dari udara; sirip perut membantu meraba lingkungan.',
                'Menjadi bagian jaring makanan perairan.', 'https://www.fishbase.org/summary/4675'],
            ['bakau', 'Bakau', 'Rhizophora mucronata', 'flora', ['hutan-mangrove'],
                'Pohon mangrove dengan akar tunjang yang mudah dikenali.',
                'Daun hijau tebal, akar tunjang bercabang, dan propagul memanjang.',
                'Pesisir berlumpur dan muara yang dipengaruhi pasang surut.',
                'Berfotosintesis dengan cahaya matahari dan menyerap air serta mineral melalui akar.',
                'Akar tunjang menopang pohon pada lumpur. Bibit berkembang pada pohon induk sebelum lepas.',
                'Membantu menahan sedimen dan memberi tempat berlindung bagi biota.', 'https://powo.science.kew.org/results?q=Rhizophora%20mucronata'],
            ['nipah', 'Nipah', 'Nypa fruticans', 'flora', ['rawa-pasang-surut', 'hutan-mangrove', 'sungai'],
                'Palem lahan basah yang tumbuh berkelompok di tepian muara.',
                'Daun majemuk panjang dan buah mengelompok berbentuk bulat.',
                'Tepi sungai pasang surut dan muara berlumpur dengan pengaruh air payau.',
                'Membuat makanan melalui fotosintesis dan menyerap mineral dari tanah.',
                'Batang menjalar di bawah permukaan dan akar membantu bertahan pada substrat berlumpur.',
                'Menjaga tepian habitat dan daunnya dapat dimanfaatkan masyarakat.', 'https://powo.science.kew.org/results?q=Nypa%20fruticans'],
            ['purun-tikus', 'Purun Tikus', 'Eleocharis dulcis', 'flora', ['rawa-lebak', 'rawa-gambut', 'rawa-pasang-surut'],
                'Tumbuhan rawa berbatang tegak yang dapat membentuk rumpun rapat.',
                'Batang hijau berbentuk silinder, bagian dalam berongga, tumbuh berumpun.',
                'Tanah tergenang dan rawa; toleransinya bergantung pada kondisi air dan tanah.',
                'Berfotosintesis dan menyerap air serta unsur hara melalui akar.',
                'Ruang udara di jaringan membantu pertukaran gas pada kondisi tergenang.',
                'Menyediakan tempat berlindung hewan kecil dan membantu menutup permukaan tanah.', 'https://powo.science.kew.org/results?q=Eleocharis%20dulcis'],
            ['bekantan', 'Bekantan', 'Nasalis larvatus', 'fauna', ['rawa-gambut', 'hutan-mangrove', 'sungai', 'rawa-pasang-surut'],
                'Primata Kalimantan yang menggunakan pepohonan hutan lahan basah sebagai tempat hidup.',
                'Bulu cokelat kemerahan, ekor panjang, dan hidung jantan dewasa lebih besar daripada betina.',
                'Hutan mangrove, hutan rawa gambut, serta hutan tepi sungai dan muara. Bukan hewan yang hidup di dalam air.',
                'Terutama daun; juga memakan bagian tumbuhan lain sesuai ketersediaannya.',
                'Tangan dan kaki membantu memegang cabang. Mampu berenang untuk melintasi perairan antartepian habitat.',
                'Bagian dari jaring makanan hutan dan membantu kita memahami pentingnya menjaga habitat tepi air.',
                'https://repository.ipb.ac.id/handle/123456789/92278'],
        ];
        foreach ($organisms as [$slug,$name,$scientific,$category,$habitats,$summary,$traits,$habitat,$nutrition,$adaptation,$benefits,$source]) {
            $o = Organism::updateOrCreate(['slug' => $slug], ['name' => $name, 'scientific_name' => $scientific, 'category' => $category,
                'summary' => $summary, 'image' => 'images/'.$slug.'.svg', 'content' => compact('traits', 'habitat', 'nutrition', 'adaptation', 'benefits', 'source')]);
            $o->ecosystems()->sync(Ecosystem::whereIn('slug', $habitats)->pluck('id'));
        }
        $questions = [
            ['mc1', 'pilihan-ganda', 'Apa yang terutama memengaruhi genangan rawa lebak?', ['Hujan dan luapan sungai', 'Salju', 'Letusan gunung setiap hari', 'Gelombang samudra'], 'Hujan dan luapan sungai', 'Rawa lebak menerima air dari hujan dan luapan sungai.'],
            ['mc2', 'pilihan-ganda', 'Mengapa gambut perlu dijaga tetap basah?', ['Agar mudah dibakar', 'Mengurangi risiko kebakaran', 'Agar menjadi pasir', 'Menghilangkan semua tumbuhan'], 'Mengurangi risiko kebakaran', 'Gambut yang mengering lebih mudah terbakar.'],
            ['mc3', 'pilihan-ganda', 'Tindakan apa yang membantu menjaga sungai?', ['Membuang plastik', 'Menggunakan racun ikan', 'Merawat vegetasi bantaran', 'Menimbun semua tepi sungai'], 'Merawat vegetasi bantaran', 'Akar tumbuhan membantu menjaga tanah di bantaran.'],
            ['mc4', 'pilihan-ganda', 'Apa fungsi akar tunjang bakau?', ['Menopang di lumpur', 'Menyerap cahaya', 'Membuat bunga', 'Menangkap burung'], 'Menopang di lumpur', 'Akar tunjang membantu bakau berdiri pada tanah lunak.'],
            ['mc5', 'pilihan-ganda', 'Ekosistem mana yang tinggi airnya berubah mengikuti pasang surut?', ['Rawa Pasang Surut', 'Padang pasir', 'Tundra', 'Padang salju'], 'Rawa Pasang Surut', 'Pasang surut memengaruhi tinggi air rawa ini.'],
        ];
        foreach ($questions as [$code,$type,$question,$options,$answer,$explanation]) {
            QuizQuestion::updateOrCreate(['code' => $code], compact('type', 'question', 'options', 'answer', 'explanation'));
        }
        foreach (Organism::all() as $o) {
            QuizQuestion::updateOrCreate(['code' => 'image-'.$o->slug], [
                'type' => 'tebak-gambar', 'question' => 'Organisme apa yang ditunjukkan pada ilustrasi ini?',
                'image' => $o->image, 'options' => Organism::where('id', '!=', $o->id)->limit(3)->pluck('name')->push($o->name)->sort()->values()->all(),
                'answer' => $o->name, 'explanation' => $o->summary]);
        }
        $badges = [
            ['pembelajar-aktif', 'Pembelajar Aktif', 'Tandai selesai materi Tentang Lahan Basah.', '📖'],
            ['pengenal-ekosistem', 'Pengenal Ekosistem', 'Selesaikan kelima ekosistem.', '🌏'],
            ['pengenal-flora', 'Pengenal Flora', 'Pelajari 3 organisme flora.', '🌿'],
            ['pengenal-fauna', 'Pengenal Fauna', 'Pelajari 3 organisme fauna.', '🐟'],
            ['ahli-perbandingan', 'Ahli Perbandingan', 'Bandingkan dua ekosistem berbeda.', '⚖'],
            ['jago-kuis', 'Jago Kuis', 'Dapatkan nilai minimal 80 pada kuis.', '🏆'],
            ['penyusun-puzzle', 'Penyusun Puzzle', 'Selesaikan puzzle ekosistem.', '🧩'],
            ['pelestari-lahan-basah', 'Pelestari Lahan Basah', 'Selesaikan materi pelestarian di kelima ekosistem.', '🌱'],
        ];
        foreach ($badges as [$slug,$name,$requirement,$icon]) {
            Badge::updateOrCreate(['slug' => $slug],compact('name','requirement','icon'));
        }
    }
}

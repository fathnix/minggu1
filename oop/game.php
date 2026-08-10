<?php

// 1. INTERFACE
// Kontrak bahwa siapapun yang mengimplementasikan ini harus punya fitur heal()
interface Healable {
    public function heal($amount);
}

// 2. ABSTRACTION
// Class kerangka dasar, tidak bisa dibuat object-nya secara langsung
abstract class Character {
    
    // 3. ENCAPSULATION
    // Properti dilindungi, hanya bisa diakses dari dalam class ini dan class anaknya
    protected $name;
    protected $hp;
    protected $attackPower;

    // STATIC PROPERTY (Menempel pada class, berguna untuk menghitung total karakter)
    public static $characterCount = 0;

    public function __construct($name, $hp, $attackPower) {
        $this->name = $name;
        $this->hp = $hp;
        $this->attackPower = $attackPower;
        self::$characterCount++; // Menambah jumlah karakter setiap kali object dibuat
    }

    // Getter untuk mengambil data yang di-protect
    public function getName() {
        return $this->name;
    }

    public function getHp() {
        return $this->hp;
    }

    public function takeDamage($damage) {
        $this->hp -= $damage;
        if ($this->hp < 0) {
            $this->hp = 0;
        }
        echo "💥 {$this->name} menerima {$damage} damage. (Sisa HP: {$this->hp})\n";
    }

    // Abstract method: Memaksa semua class anak untuk membuat fungsi ini dengan cara mereka sendiri
    abstract public function attack(Character $target);
}

// 4. INHERITANCE (Hero mewarisi Character)
class Hero extends Character implements Healable {
    private $mana; // Properti khusus Hero

    public function __construct($name, $hp, $attackPower, $mana) {
        // Memanggil constructor dari class induk (Character)
        parent::__construct($name, $hp, $attackPower); 
        $this->mana = $mana;
    }

    // 5. POLYMORPHISM
    // Implementasi cara serang khusus Hero
    public function attack(Character $target) {
        echo "⚔️ Hero {$this->name} menebas {$target->getName()}!\n";
        $target->takeDamage($this->attackPower);
    }

    // Implementasi wajib dari interface Healable
    public function heal($amount) {
        if ($this->mana >= 10) {
            $this->hp += $amount;
            $this->mana -= 10;
            echo "✨ {$this->name} menggunakan sihir penyembuh! (+{$amount} HP). Sisa Mana: {$this->mana}\n";
        } else {
            echo "❌ Mana {$this->name} tidak cukup untuk Heal!\n";
        }
    }
}

// INHERITANCE (Monster mewarisi Character)
class Monster extends Character {
    
    // POLYMORPHISM
    // Implementasi cara serang khusus Monster (Damage-nya acak/random)
    public function attack(Character $target) {
        echo "👹 Monster {$this->name} menggigit {$target->getName()}!\n";
        $criticalDamage = $this->attackPower + rand(0, 10);
        $target->takeDamage($criticalDamage);
    }
}

// ==========================================
// SIMULASI PROGRAM (Bagian Eksekusi)
// ==========================================

echo "=== PERTARUNGAN DIMULAI ===\n\n";

// Membuat Object
$hero = new Hero("Arthur", 100, 25, 30);
$monster = new Monster("Orc", 80, 15);

echo "Total Karakter di Arena: " . Character::$characterCount . "\n";
echo "------------------------------\n";

// Simulasi Aksi
$hero->attack($monster);
echo "------------------------------\n";
$monster->attack($hero);
echo "------------------------------\n";
$hero->heal(20);
echo "------------------------------\n";
$hero->attack($monster);
echo "------------------------------\n";

// Pengecekan Pemenang Sederhana
if ($monster->getHp() == 0) {
    echo "🏆 {$hero->getName()} BERHASIL MENGALAHKAN MONSTER!\n";
} else {
    echo "Pertarungan masih berlanjut...\n";
}

?>
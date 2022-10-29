<?php
require_once 'Main.php';
use PHPUnit\Framework\TestCase;
 
class AdventureControllerTest extends TestCase
{
   public function setUp() : void {
        $this->equal_options = array(
            array("A"=> "You run as fast as you can, and you almost catch up to the bus.",
                  "B"=> "You run as fast as you can, but you can't seem to gain any ground catching up to the bus."
                 ),
            array("A"=> "You run with all of your might, and the driver sees you in the mirror waving, and lets you on.",
                  "B" => "You step in a puddle and it slows you down.",
                 ),
            array("A"=> "The bus pulls over out of pity and lets you on.",
                  "B" => "You couldn't catch up, the bus drives away."
                 )
        );
        
        $this->character_advantage_options = array(
            array("A"=> "The bus is pulling away, but you are pretty fast and easily catch it.",
                  "B"=> "The bus is pulling away, and even though you are pretty fast, you can't seem to catch up."
                 ),
            array("A"=> "Although harder than you expected, you put your head down and eventually catch up to the bus, getting on.",
                  "B"=>"You are surprised to find you are not as fast as you once were."
                 )
        );
        
        $this->challenge_advantage_options = array(
            array("B"=> "The bus is pulling away, and you are far to slow to really catch it, and it drives out of sight.",
                  "A"=> "The bus is pulling away, and even though you are pretty slow, you seem to gain ground."
                 ),
            array("B"=> "But try as you might, it eventually gets away.",
                  "A"=>"By some miracle, you actually flag down the driver to stop, and get on."
                 )
        );
        $this->bus_challenge = new ChallengeModel(
            "bus_challenge",
            "You are attempting to catch the bus, but you are late, and it is pulling away from the stop.",
            "Speed",
            "HIGH",
            "seat_challenge",
            "_done",
            $this->equal_options,
            $this->character_advantage_options,
            $this->challenge_advantage_options
        );
        
        $this->bus_challenge1 = new ChallengeModel(
            "bus_challenge1",
            "You are attempting to catch the bus, but you are late, and it is pulling away from the stop.",
            "Speed",
            "HIGH",
            "seat_challenge",
            "_done",
            $this->equal_options,
            $this->character_advantage_options,
            $this->challenge_advantage_options
        );
        
        $this->bus_challenge2 = new ChallengeModel(
            "bus_challenge",
            "You are attempting to catch the bus, but you are late, and it is pulling away from the stop.",
            "Speed",
            "HIGH",
            "seat_challenge",
            "_done",
            $this->equal_options,
            $this->character_advantage_options,
            $this->challenge_advantage_options
        );
        
        $this->start = new ChallengeModel(
            "_start",
            "You are attempting to catch the bus, but you are late, and it is pulling away from the stop.",
            "Speed",
            "HIGH",
            "after_start",
            "after_start",
            $this->equal_options,
            $this->character_advantage_options,
            $this->challenge_advantage_options
        );
        
        $this->afterStart = new ChallengeModel(
            "after_start",
            "You are attempting to catch the bus, but you are late, and it is pulling away from the stop.",
            "Speed",
            "HIGH",
            "next_is_end",
            "next_is_end",
            $this->equal_options,
            $this->character_advantage_options,
            $this->challenge_advantage_options
        );
        
        $this->nextIsEnd = new ChallengeModel(
            "next_is_end",
            "You are attempting to catch the bus, but you are late, and it is pulling away from the stop.",
            "Speed",
            "HIGH",
            "_end",
            "_end",
            $this->equal_options,
            $this->character_advantage_options,
            $this->challenge_advantage_options
        );
        
        
        $equal_options = array(
            array(
                "A" => "equal_options_1_succees",
                "B" => "equal_options_1_fail"
            ),
            array(
                "A" => "equal_options_2_succees",
                "B" => "equal_options_2_fail"
            ),
            array(
                "A" => "equal_options_3_succeed",
                "B" => "equal_options_3_failure"
            )
        );
        
        $character_advantage_options = array(
            array(
                "A" => "character_advantage1_succeed",
                "B" => "character_advantage1_failure"
            ), 
            array(
                "A" => "character_advantage_options_2_succees",
                "B" => "character_advantage_options_2_fail"
            )
        );
        
        $challenge_advantage_options = array(
            array(
                "A" => "challenge_advantage1_succeed",
                "B" => "challenge_advantage1_failure"
            ),
            array(
                "A" => "challenge_advantage_options_2_succees",
                "B" => "challenge_advantage_options_2_fail"
            )
        );
        
        $this->startAdv = new ChallengeModel(
            "_start",
            "START_ADV",
            "LUCK",
            "HIGH",
            "NEXT_1_SUCCEED",
            "NEXT_1_FAILURE",
            $equal_options,
            $character_advantage_options,
            $challenge_advantage_options
        );
        
        $this->next1succeed = new ChallengeModel(
            "NEXT_1_SUCCEED",
            "NEXT_1_SUCCEED",
            "SPEED",
            "LOW",
            "NEXT_2",
            "NEXT_2",
            $equal_options,
            $character_advantage_options,
            $challenge_advantage_options
        );
        
        $this->next1failure = new ChallengeModel(
            "NEXT_1_FAILURE",
            "NEXT_1_FAILURE",
            "SPEED",
            "HIGH",
            "NEXT_2",
            "NEXT_2",
            $equal_options,
            $character_advantage_options,
            $challenge_advantage_options
        );
        
        $this->next2 = new ChallengeModel(
            "NEXT_2",
            "NEXT_2",
            "STRENGTH",
            "LOW",
            "NEXT_3",
            "NEXT_3",
            $equal_options,
            $character_advantage_options,
            $challenge_advantage_options
        );
        
        $this->next3 = new ChallengeModel(
            "NEXT_3",
            "NEXT_3",
            "STRENGTH",
            "HIGH",
            "_end",
            "_end",
            $equal_options,
            $character_advantage_options,
            $challenge_advantage_options
        );
    }
    
        private function rng_stub($value): object
    {
        $stub = $this->createStub(RNG::class);
        $stub->method("choose")->willReturn($value);
        return $stub;
    }
        private function gen_bro($value): BinaryRandomOracle
    {
        return new BinaryRandomOracle($this->rng_stub($value));
    }
    
    protected function tearDown(): void {
        $this->resetCharacterModel();
    }
    
    private function resetCharacterModel(): void {
        $class = new ReflectionClass(CharacterModel::class);
        $class->setStaticPropertyValue("available_attributes", array());
    }
    
    public function gen_adventure_controller() {
        $bro = new BinaryRandomOracle(new RNG());
        $character = new CharacterModel($bro, "Maen", 0);
        $challenge_database = array();
        
        $sut = new AdventureController($character, $challenge_database);
 
        return $sut;
    }
    
    
    public function test_class_attribute(){
        $sut = $this->gen_adventure_controller();
        
        $this->assertClassHasAttribute("character", AdventureController::class);
        $this->assertClassHasAttribute("challenge_database", AdventureController::class);
    
        $this->assertEmpty($sut->get_challenge_database());
    }
    
    public function test_add_challenge_unique_id() {
        $this->expectException(InvalidArgumentException::class);
        $sut = $this->gen_adventure_controller();
        
        $sut->add_challenge($this->bus_challenge);
        $sut->add_challenge($this->bus_challenge2);
    }
    
    public function test_add_challenge_id_check() {
        $this->expectException(InvalidArgumentException::class);
        $sut = $this->gen_adventure_controller();
        
        $bad_challenge = new ChallengeModel(NULL, "", "", "", "", "", array(), array(), array());
        $sut->add_challenge($bad_challenge);
    }
    
    public function test_add_challenge_morethan_5() {
        $sut = $this->gen_adventure_controller();
        
        $bus_challenge0 = new ChallengeModel("0", "", "", "", "", "", $this->equal_options, $this->character_advantage_options, $this->challenge_advantage_options);
        $bus_challenge1 = new ChallengeModel("1", "", "", "", "", "", $this->equal_options, $this->character_advantage_options, $this->challenge_advantage_options);
        $bus_challenge2 = new ChallengeModel("2", "", "", "", "", "", $this->equal_options, $this->character_advantage_options, $this->challenge_advantage_options);
        $bus_challenge3 = new ChallengeModel("3", "", "", "", "", "", $this->equal_options, $this->character_advantage_options, $this->challenge_advantage_options);
        $bus_challenge4 = new ChallengeModel("4", "", "", "", "", "", $this->equal_options, $this->character_advantage_options, $this->challenge_advantage_options);
        $bus_challenge5 = new ChallengeModel("5", "", "", "", "", "", $this->equal_options, $this->character_advantage_options, $this->challenge_advantage_options);
        
        $sut->add_challenge($bus_challenge0);
        $sut->add_challenge($bus_challenge1);
        $sut->add_challenge($bus_challenge2);
        $sut->add_challenge($bus_challenge3);
        $sut->add_challenge($bus_challenge4);
        $sut->add_challenge($bus_challenge5);
        
        $this->assertSame(count($sut->get_challenge_database()), 5);
    }
    
    public function test_add_challenge_wrong_options_length() {
        $sut = $this->gen_adventure_controller();
        
        // equal_options has less than 3 options
        $bus_challenge = new ChallengeModel("0", "", "", "", "", "", array(), $this->character_advantage_options, $this->challenge_advantage_options);
        $sut->add_challenge($bus_challenge);
        
        $this->assertEmpty($sut->get_challenge_database());
        
        // character_advantage_options has less than 2 options
        $bus_challenge = new ChallengeModel("0", "", "", "", "", "", $this->equal_options, array(), $this->challenge_advantage_options);
        $sut->add_challenge($bus_challenge);
        
        $this->assertEmpty($sut->get_challenge_database());
        
        // challenge_advantage_options has less than 2 options
        $bus_challenge = new ChallengeModel("0", "", "", "", "", "", $this->equal_options, $this->character_advantage_options, array());
        $sut->add_challenge($bus_challenge);
        
        $this->assertEmpty($sut->get_challenge_database());
    }
    
    public function test_remove_challenge_with_id_does_not_exist() {
        $sut = $this->gen_adventure_controller();
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("ID does not exist");
        
        $sut->add_challenge($this->bus_challenge);
        $sut->remove_challenge("1");
    }
    
    public function test_remove_challenge_empty_database() {
        $sut = $this->gen_adventure_controller();
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Database is empty");
        
        $sut->remove_challenge("1");
    }
    
    public function test_remove_challenge_clear_database() {
        $sut = $this->gen_adventure_controller();
        
        $sut->add_challenge($this->bus_challenge);
        $this->assertSame(count($sut->get_challenge_database()), 1);
        
        $res = $sut->remove_challenge(NULL);
        $this->assertSame($res, true);
        
        $this->assertSame(count($sut->get_challenge_database()), 0);
    }
    
    public function test_remove_challenge() {
        $sut = $this->gen_adventure_controller();
        
        $sut->add_challenge($this->bus_challenge);
        $sut->add_challenge($this->bus_challenge1);
        $this->assertSame(count($sut->get_challenge_database()), 2);
        
        $res = $sut->remove_challenge("bus_challenge");
        $this->assertSame($res, true);
        $this->assertSame(count($sut->get_challenge_database()), 1);
    }
    
    public function test_validate_adventure_no__start() {
        $sut = $this->gen_adventure_controller();
        $sut->add_challenge($this->bus_challenge);
        
        $res = $sut->validate_adventure();
        $this->assertSame($res, false);
    }
    
    public function test_validate_adventure_no_next__end() {
        $sut = $this->gen_adventure_controller();
        $sut->add_challenge($this->start);
        
        $res = $sut->validate_adventure();
        $this->assertSame($res, false);
    }
    
    public function test_validate_adventure_no_attribute() {
        $bro = new BinaryRandomOracle(new RNG());
        CharacterModel::add_available_attribute("Speed");
        $character = new CharacterModel($bro, "Maen", 1);
        $challenge_database = array();
        
        $sut = new AdventureController($character, $challenge_database);
        
        $res = $sut->validate_adventure();
        $this->assertSame($res, false);
    }
    
    public function test_validate_adventure() {      
        $bro = new BinaryRandomOracle(new RNG());
        CharacterModel::add_available_attribute("Speed");
        $character = new CharacterModel($bro, "Maen", 1);
        $challenge_database = array();
        
        $sut = new AdventureController($character, $challenge_database);
        
        $sut->add_challenge($this->start);
        $sut->add_challenge($this->afterStart);
        $sut->add_challenge($this->nextIsEnd);
        
        $res = $sut->validate_adventure();
        $this->assertSame($res, true);
    }
    
    public function test_validate_no_way_to_end() {
        $bro = new BinaryRandomOracle(new RNG());
        CharacterModel::add_available_attribute("Speed");
        $character = new CharacterModel($bro, "Maen", 1);
        $challenge_database = array();
        
        $sut = new AdventureController($character, $challenge_database);
        
        $sut->add_challenge($this->start);
        $sut->add_challenge($this->nextIsEnd);
        
        $res = $sut->validate_adventure();
        $this->assertSame($res, false);
    }
    #------------------------------------------------
     public function test_navigate_adventure() {
        CharacterModel::add_available_attribute("LUCK");
        CharacterModel::add_available_attribute("SPEED");
        CharacterModel::add_available_attribute("STRENGTH");
        
        $bro = $this->gen_bro(0);
        $character = new CharacterModel($bro, "Maen", 3);
        $challenge_database = array();
        
        $sut = new AdventureController($character, $challenge_database);
        
        $sut->add_challenge($this->startAdv);
        $sut->add_challenge($this->next1succeed);
        $sut->add_challenge($this->next1failure);
        $sut->add_challenge($this->next2);
        $sut->add_challenge($this->next3);
        
        // All challenges are successful
        $res_adventure = $sut->navigate_adventure($bro);
        
        // Check brownie points
        $this->assertSame($sut->get_character()->get_brownie_points(), 4);
        
        // Check story
        $this->assertSame($res_adventure, array(
            "equal_options_1_succees",
            "equal_options_2_succees",
            "character_advantage1_succeed",
            "character_advantage1_succeed",
            "equal_options_1_succees",
            "equal_options_2_succees"
        ));
        
        
        $character = new CharacterModel($bro, "Maen", 3);
        $bro = $this->gen_bro(1);
        $challenge_database = array();
        
        $sut = new AdventureController($character, $challenge_database);
        
        $sut->add_challenge($this->startAdv);
        $sut->add_challenge($this->next1succeed);
        $sut->add_challenge($this->next1failure);
        $sut->add_challenge($this->next2);
        $sut->add_challenge($this->next3);
        
        $res_adventure = $sut->navigate_adventure($bro);
        
        $this->assertSame($sut->get_character()->get_brownie_points(), 0);
        
        $this->assertEqualsCanonicalizing($sut->get_character()->get_attributes(), array(
            "LUCK" => "LOW",
            "SPEED" => "LOW",
            "STRENGTH" => "LOW"
        ));
        
        $this->assertSame($res_adventure, array(
            "equal_options_1_fail",
            "equal_options_2_fail",
            "equal_options_1_fail",
            "equal_options_2_fail",
            "character_advantage1_failure",
            "character_advantage_options_2_fail",
            "challenge_advantage1_failure",
        ));
        
        $bro = $this->gen_bro(0);
        $character = new CharacterModel($bro, "Maen", 3);
        
        $next3 = new ChallengeModel(
            "NEXT_3",
            "NEXT_3",
            "STRENGTH",
            "HIGH",
            "NEXT_4_SUCCEED", 
            "NEXT_4_FAILURE",
            array(
                array(
                    "A" => "NEXT_3_1_SUCCEED",
                    "B" => "NEXT_3_1_FAILURE"
                ),
                array(
                    "A" => "NEXT_3_2_SUCCEED",
                    "B" => "NEXT_3_2_FAILURE"
                ),
                array(
                    "A" => "NEXT_3_3_SUCCEED",
                    "B" => "NEXT_3_3_FAILURE"
                )
            ),
            $this->character_advantage_options,
            $this->challenge_advantage_options
        );
        
        $next4_succeed = new ChallengeModel(
            "NEXT_4_SUCCEED",
            "NEXT_4_SUCCEED",
            "STRENGTH",
            "HIGH",
            "_end",
            "_end",
            array(
                array(
                    "A" => "NEXT_4_1_SUCCEED",
                    "B" => "NEXT_4_1_FAILURE"
                ),
                array(
                    "A" => "NEXT_4_2_SUCCEED",
                    "B" => "NEXT_4_2_FAILURE"
                ),
                array(
                    "A" => "NEXT_4_3_SUCCEED",
                    "B" => "NEXT_4_3_FAILURE"
                )
            ),
            $this->character_advantage_options,
            $this->challenge_advantage_options
        );
        
        $challenge_database = array();
        
        $sut = new AdventureController($character, $challenge_database);
        
        $sut->add_challenge($this->startAdv);
        $sut->add_challenge($this->next1succeed);
        $sut->add_challenge($this->next2);
        $sut->add_challenge($next3);
        $sut->add_challenge($next4_succeed);
        
        $gen_bro_mixed = function() {
            $stub = $this->createStub(RNG::class);
            $stub->method("choose")->willReturn(0, 0, 0, 0, 1, 1, 0, 1, 0);
            
            return new BinaryRandomOracle($stub);
        };
        
        $bro = $gen_bro_mixed();
        
        $res_adventure = $sut->navigate_adventure($bro);
        
        $this->assertSame($sut->get_character()->get_brownie_points(), 1);
        
        $this->assertEqualsCanonicalizing($sut->get_character()->get_attributes(), array(
            "LUCK" => "HIGH",
            "SPEED" => "HIGH",
            "STRENGTH" => "HIGH"
        ));
        
        $this->assertSame($res_adventure, array(
            "equal_options_1_succees",
            "equal_options_2_succees",
            "character_advantage1_succeed",
            "character_advantage1_succeed",
            "NEXT_3_1_FAILURE",
            "NEXT_3_2_FAILURE",
            "NEXT_4_1_SUCCEED",
            "NEXT_4_2_FAILURE",
            "NEXT_4_3_SUCCEED"
        ));
     }
}
 
 
?>

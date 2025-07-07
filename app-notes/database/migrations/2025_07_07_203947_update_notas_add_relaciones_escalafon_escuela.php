use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNotasAddRelacionesEscalafonEscuela extends Migration
{
    public function up()
    {
        Schema::table('notas', function (Blueprint $table) {
            // Eliminar columnas antiguas si existen
            if (Schema::hasColumn('notas', 'escuela')) {
                $table->dropColumn('escuela');
            }
            if (Schema::hasColumn('notas', 'tipo')) {
                $table->dropColumn('tipo');
            }
            // Agregar nuevas relaciones
            $table->unsignedBigInteger('escuela_id')->nullable()->after('observaciones');
            $table->unsignedBigInteger('escalafon_id')->nullable()->after('escuela_id');
            $table->foreign('escuela_id')->references('id')->on('escuelas')->onDelete('set null');
            $table->foreign('escalafon_id')->references('id')->on('escalafon')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('notas', function (Blueprint $table) {
            // Eliminar las columnas y las relaciones
            $table->dropColumn('escuela_id');
            $table->dropColumn('escalafon_id');
            $table->dropForeign('escuela_id');
            $table->dropForeign('escalafon_id');
        });
    }
} 
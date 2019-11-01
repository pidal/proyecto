<?php


namespace pfg\Models;


class PruebasUnitarias
{

    public function executeLanguageC($fileInstructor, StudentFile $studentFile)
    {
        $path_completo = storage_path('TODO'.DIRECTORY_SEPARATOR . $studentFile->id . '_' . $studentFile->left_attempts);

        chdir($path_completo);
        chmod($path_completo, 0777);

        $exec = 'gcc ' . $path_completo.DIRECTORY_SEPARATOR.$fileInstructor . ' -I/lib/include -lcunit  -o ' . $path_completo.'/'.$studentFile->fileName;

        //dd($exec);

        //dd($exec);
        //gcc: error: /var/www/html/proyecto/storage/TODO/346_3/sum_test.c: No such file or directory

        /*
        desde subir archivo en profesor:

        /var/www/html/proyecto/storage/TODO/391_10<br>/391_10/var/www/html/proyecto/storage/TODO/392_10<br>/392_10

        gcc comando:

        gcc /var/www/html/proyecto/storage/TODO/391_10/suma.c -I/lib/include -lcunit  -o /var/www/html/proyecto/storage/TODO/391_10/suma.c

        gcc output
        
        este error sucede por que nombre los archivos iguales
        gcc: gcc: fatal error: input file '/var/www/html/proyecto/storage/TODO/391_10/suma.c' is the same as output file
compilation terminated.
fatal error: input file '/var/www/html/proyecto/storage/TODO/391_10/suma.c' is the same as output file
compilation terminated.


        */

        shell_exec($exec);

        dd();

        //Ejecutamos el archivo creado de la compilación
        $ejecutable = $path_completo.'/' . $studentFile->fileName;
        //dd($ejecutable);
        $salida = $this->PsExecute($ejecutable);

        if ($salida == false) {
            Session::flash('error', 'ERROR: El archivo adjunto puede contener bucles infinitos');
            return redirect('/showStudentsFiles');
        }
        //dd($path_completo.DIRECTORY_SEPARATOR.'CUnitAutomated-Results.xml');

        $xml = simplexml_load_file($path_completo.DIRECTORY_SEPARATOR.'CUnitAutomated-Results.xml');
        $studentFile->total = $xml->CUNIT_RUN_SUMMARY->CUNIT_RUN_SUMMARY_RECORD[2]->TOTAL;
        $studentFile->pass = $xml->CUNIT_RUN_SUMMARY->CUNIT_RUN_SUMMARY_RECORD[2]->SUCCEEDED;
        $studentFile->fails = $xml->CUNIT_RUN_SUMMARY->CUNIT_RUN_SUMMARY_RECORD[2]->FAILED;
        $studentFile->score = ($studentFile->pass / $studentFile->total) * 10;
        return $studentFile;
    }

    public function executeLanguageJava($fileInstructor,StudentFile $studentFile)
    {
        $path_completo = storage_path('TODO'.DIRECTORY_SEPARATOR . $studentFile->id . '_' . $studentFile->left_attempts);
        $execCompileStudent = 'javac ' . $path_completo.DIRECTORY_SEPARATOR.$studentFile->fileName;
        shell_exec($execCompileStudent);
        $execCompileInstructor = 'javac -cp '.public_path(DIRECTORY_SEPARATOR.'junit.jar:. '). $path_completo.DIRECTORY_SEPARATOR.$fileInstructor;







        shell_exec($execCompileInstructor);

        $fileInstructorRun = basename($fileInstructor, ".java");

        $execRun = 'java -cp .:'.public_path().'/junit.jar:'.public_path().'/hamcrest.jar org.junit.runner.JUnitCore ' . $fileInstructorRun . ' >output.txt';


        //dd($execRun);


        shell_exec($execRun);
        $myfile = fopen("output.txt", "r") or die("Unable to open file!");
        $lines = file("output.txt");
        $KO = 'Failures:';
        $OK = 'OK';
        foreach ($lines as $line) {
            if (strpos($line, $KO) !== false) {
                $line = preg_split('/[^\d]/', $line, -1, PREG_SPLIT_NO_EMPTY);
                $studentFile->total = $line[0];
                $studentFile->fails = $line[1];
                $studentFile->pass = $studentFile->total - $studentFile->fails;
                $studentFile->score = ($studentFile->pass / $studentFile->total) * 10;
            } elseif (strpos($line, $OK) !== false) {
                $line = preg_split('/[^\d]/', $line, -1, PREG_SPLIT_NO_EMPTY);
                $studentFile->total = $line[0];
                $studentFile->fails = '0';
                $studentFile->pass = $studentFile->total - $studentFile->fails;
                $studentFile->score = ($studentFile->pass / $studentFile->total) * 10;
            }
        }
        return $studentFile;
    }

    public function executeLanguageCsharp($fileInstructor,StudentFile $studentFile){

        $project_name = $studentFile->id . '_' . $studentFile->left_attempts;
        $path_completo = storage_path('TODO'.DIRECTORY_SEPARATOR . $project_name.DIRECTORY_SEPARATOR);

        //Create the solution
        $exec = "dotnet new classlib --force -o ".$path_completo;
        $env = array('DOTNET_CLI_HOME' => '/var/www','HOME'=>'/var/www');
        $result = $this->shellExecute($exec, $env);

        $exec = <<<EOF
            echo '<Project Sdk="Microsoft.NET.Sdk"><PropertyGroup><TargetFramework>netcoreapp2.1</TargetFramework></PropertyGroup><ItemGroup><PackageReference Include="xunit" Version="2.4.1" /><PackageReference Include="xunit.runner.visualstudio" Version="2.4.1" /></ItemGroup></Project>' > {$path_completo}{$project_name}.csproj
EOF;
        $result = $this->shellExecute($exec);

        $exec = "dotnet test ".$path_completo." > ".$path_completo."resultado.txt";
        $salida = $this->shellExecute($exec,$env);

        $studentFile = $this->getOutputCsharp($studentFile,$path_completo."resultado.txt");

        return $studentFile;
    }

    private function getOutputCsharp($studentFile,$file){
        $salida = file_get_contents($file);
        if(empty($salida)) throw new \Exception('El resultado de las pruebas ha sido nulo');

        preg_match('~Total tests: (\d+)~', $salida, $total );
        preg_match('~Passed: (\d+)~', $salida, $passed );
        preg_match('~Failed: (\d+)~', $salida, $failed );

        $total = isset($total[1]) ? $total[1] : 0;
        $passed = isset($passed[1]) ? $passed[1] : 0;
        $failed = isset($failed[1]) ? $failed[1] : 0;

        $studentFile->total = $total;
        $studentFile->pass = $passed;
        $studentFile->fails = $failed;
        $studentFile->score =  $studentFile->total > 0 ? ($studentFile->pass / $studentFile->total) * 10 : 0;
        return $studentFile;

    }

    private function shellExecute($exec, $env= array()){
        $return_value = '';
        $cwd = '/tmp';
        $descriptorspec = array(
            0 => array("pipe", "r"),  // stdin es una tubería usada por el hijo para lectura
            1 => array("pipe", "w"),  // stdout es una tubería usada por el hijo para escritura
            2 => array("file", "/tmp/error-output.txt", "a") // stderr es un fichero para escritura
        );
        $process = proc_open($exec, $descriptorspec, $pipes, $cwd, $env);
        if (is_resource($process)) {
            fwrite($pipes[0], '<?php print_r($_ENV); ?>');
            fclose($pipes[0]);
            fclose($pipes[1]);
            $return_value = proc_close($process);
        }
        return $return_value;
    }

    private function PsExecute($command, $timeout = 8, $sleep = 2)
    {
        // First, execute the process, get the process ID
        $pid = $this->PsExec($command);
        if ($pid === false)
            return false;

        $cur = 0;
        // Second, loop for $timeout seconds checking if process is running
        while ($cur < $timeout) {
            sleep($sleep);
            $cur += $sleep;
            // If process is no longer running, return true;
            if (!$this->PsExists($pid))
                return true; // Process must have exited, success!
        }

        // If process is still running after timeout, kill the process and return false
        $this->PsKill($pid);
        return false;
    }

    private function PsExec($commandJob)
    {
        $command = $commandJob . ' > /dev/null 2>&1 & echo $!';
        exec($command, $op);
        $pid = (int)$op[0];
        if ($pid != "") return $pid;
        return false;
    }

    private function PsExists($pid)
    {
        exec("ps ax | grep $pid 2>&1", $output);
        foreach ($output as $row) {
            $row_array = explode(" ", $row);
            $check_pid = $row_array[0];
            if ($pid == $check_pid) {
                return true;
            }
        }
        return false;
    }

}

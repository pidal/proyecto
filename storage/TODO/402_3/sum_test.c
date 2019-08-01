#include <CUnit/Basic.h>
#include <CUnit/CUnit.h>
#include <CUnit/Automated.h>
#include <CUnit/Basic.h>	
#include <CUnit/Console.h>	

#include "suma.c"

void sum_test() {
  // Check if first param matches with second
  CU_ASSERT_EQUAL(sum(2, 14), 16);
  CU_ASSERT_EQUAL(sum(0, 0), 0);
  CU_ASSERT_EQUAL(sum(-1 ,6), 5);
}

int main() {
   // Initialize the CUnit test registry
   if (CUE_SUCCESS != CU_initialize_registry())
      return CU_get_error();

   CU_pSuite pSuite = NULL;

   // Add a suite to the registry
   pSuite = CU_add_suite("sum_test_suite", 0, 0);

   // Always check if add was successful
   if (NULL == pSuite) {
     CU_cleanup_registry();
     return CU_get_error();
   }

   // Add the test to the suite
   if (NULL == CU_add_test(pSuite, "sum_test", sum_test)) {
     CU_cleanup_registry();
     return CU_get_error();
   }

   // Sets the basic run mode, CU_BRM will show maximum output of run details
   // Other choices are: CU_BRM_SILENT and CU_BRM_NORMAL
   // CU_basic_run_tests();
   //CU_console_run_tests();
   CU_automated_run_tests();

   // Run the tests and show the run summary
   CU_basic_run_tests();
   return CU_get_error();
}

package net.javaVillage;

import static org.junit.Assert.assertEquals;
import org.junit.After;
import org.junit.Before;
import org.junit.Test;

public class AdditionTest {
private Addition addition;

/** * Initialization */
@Before
public void setUp() {
addition = new Addition();
}

/** * Test case for add method */
@Test
public void test() {
int i = addition.add(3, 7);
assertEquals(10, i);
}

/** * destroy the object */
@After
public void tearDown() {
addition = null;
}
}
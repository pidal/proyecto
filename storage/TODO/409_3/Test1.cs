using Xunit;

namespace MyFirstUnitTests
{
    public class Test1
    {
        [Fact]
        public void PassingTest()
        {
            Class1 myCs = new Class1();
            Assert.Equal(4, myCs.Add(2, 2));
        }

        [Fact]
        public void FailingTest()
        {
            Class1 myCs = new MyFirstUnitTests.Class1();
            Assert.Equal(4, myCs.Add(2, 2));
        }

    }
}

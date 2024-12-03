using Microsoft.EntityFrameworkCore;

namespace ARBiblioteket.ApiService.Services
{
    public class ApplicationDbContext : DbContext
    {
        private readonly IConfiguration _configuration;

        public ApplicationDbContext(IConfiguration configuration)
        {
            _configuration = configuration;
        }

        public DbSet<User> Users { get; set; }
        public DbSet<Model> Models { get; set; }

        protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
        {
            optionsBuilder.UseMySql(
                _configuration.GetConnectionString("DefaultConnection"),
                new MySqlServerVersion(new Version(8, 0, 40))
            );
        }
    }
}

public interface IModelService
{
    Task<Model?> GetModel(int id);
    Task<List<Model>> GetAllModels();
    Task<Model> CreateModel(ModelDto modelDto);
    Task<bool> UpdateModel(int id, ModelDto modelDto);
    Task<bool> DeleteModel(int id);
} 
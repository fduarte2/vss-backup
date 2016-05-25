// This is a "safe" class, meaning that it is created once 
// and never overwritten. Any custom code you add to this class 
// will be preserved when you regenerate your application.
//
// Typical customizations that may be done in this class include
//  - adding custom event handlers
//  - overriding base class methods

using System;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;

namespace ePortDC.Business
{

/// <summary>
/// Provides access to the schema information and record data of a database table (or view).
/// See <see cref="BaseDC_UserTable"></see> for additional information.
/// </summary>
/// <remarks>
/// See <see cref="BaseDC_UserTable"></see> for additional information.
/// <para>
/// This class is implemented using the Singleton design pattern.
/// </para>
/// </remarks>
/// <seealso cref="BaseDC_UserTable"></seealso>
/// <seealso cref="BaseDC_UserSqlTable"></seealso>
/// <seealso cref="DC_UserSqlTable"></seealso>
/// <seealso cref="DC_UserDefinition"></seealso>
/// <seealso cref="DC_UserRecord"></seealso>
/// <seealso cref="BaseDC_UserRecord"></seealso>
[SerializableAttribute()]
public class DC_UserTable : BaseDC_UserTable, System.Runtime.Serialization.ISerializable, ISingleton
{

#region "ISerializable Members"

    /// <summary>
    /// Overridden to use the <see cref="DC_UserTable_SerializationHelper"></see> class 
    /// for deserialization of <see cref="DC_UserTable"></see> data.
    /// </summary>
    /// <remarks>
    /// Since the <see cref="DC_UserTable"></see> class is implemented using the Singleton design pattern, 
    /// this method must be overridden to prevent additional instances from being created during deserialization.
    /// </remarks>
    void System.Runtime.Serialization.ISerializable.GetObjectData(
        System.Runtime.Serialization.SerializationInfo info, 
        System.Runtime.Serialization.StreamingContext context)
    {
        info.SetType(typeof(DC_UserTable_SerializationHelper)); //No other values need to be added
    }

#region "Class DC_UserTable_SerializationHelper"

    [SerializableAttribute()]
    private class DC_UserTable_SerializationHelper: System.Runtime.Serialization.IObjectReference
    {
        //Method called after this object is deserialized
        public virtual object GetRealObject(System.Runtime.Serialization.StreamingContext context)
        {
            return DC_UserTable.Instance;
        }
    }

#endregion

#endregion

    /// <summary>
    /// References the only instance of the <see cref="DC_UserTable"></see> class.
    /// </summary>
    /// <remarks>
    /// Since the <see cref="DC_UserTable"></see> class is implemented using the Singleton design pattern, 
    /// this field is the only way to access an instance of the class.
    /// </remarks>
    public readonly static DC_UserTable Instance = new DC_UserTable();

    private DC_UserTable()
    {
    }


} // End class DC_UserTable

}
